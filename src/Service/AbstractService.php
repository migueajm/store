<?php

namespace App\Service;

use App\Exception\FormException;
use App\Exception\UnauthorizedException;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AbstractService extends AbstractController
{
	private $res = null;
	private ?int $code;
	const FORMAT_DATE = 'Y-m-dTH:i:s';

	public function getSession(): SessionInterface
	{
		return $this->getRequest()->getSession();
	}

	public function getBody($form = null): ?object
	{
		if ($form) {
			$entity = $form->handleRequest($this->getRequest())->getData();
			return $entity;
		}
		if ($this->getRequest()->headers->get('Content-Type') !== 'application/json') {
			return null;
		}
		return json_decode($this->getRequest()->getContent());
	}

	public function getQueryParams(): object
	{
		return (object)$this->getRequest()->query->all();
	}

	public function getHeaders(): object
	{
		return (object)$this->getRequest()->headers->all();
	}

	public function getRequest(): Request
	{
		$request = $this->container->get('request_stack')->getCurrentRequest();
		return $request;
	}

	/**
	 * Validates if the user has access to the given module.
	 *
	 * @throws NotFoundResourceException If the user or controller is not found, or the user lacks access.
	 */
	public function validateUserControllerAccess(string $class): void
	{
		$classNameParts = explode('\\', $class);
		$class = end($classNameParts);
		$user = $this->getUser();
		if (!$user) {
			throw new NotFoundResourceException("Resource not found.", Response::HTTP_NOT_FOUND);
		}
		$method = "getModules";
		$modules = $user->$method();
		$isset = false;
		foreach ($modules as &$module) {
			if ($module['class'] === $class) {
				$module['active'] = true;
				$isset = true;
				break;
			}
		}
		if (!$isset) {
			throw new NotFoundResourceException("Resource not found.", Response::HTTP_NOT_FOUND);
		}
		$session = $this->getSession();
		if (!$session->get('user')) {
			$session->set('user', [
				'modules' => $modules,
				'username' => '@' . $user->getUserIdentifier()
			]);
		}
	}

	public function getModuleAndTableProperties(array $text, array $head, string $action, array $modal): array
	{
		$title = $text[0];
		$module = [
			'title' => "Gestión de {$text[1]}",
			'id' => "btn-$action",
			'action' => $text[2],
			'href' => "#{$action}"
		];
		$table = [
			'id' => $text['3'],
			'head' => $head,
			'body' => "No hay {$text[1]} registrados",
			'count' => count($head)
		];
		return compact('title', 'module', 'table', 'modal');
	}

	/**
	 * @return object|array|null
	 */
	public function getRes()
	{
		return $this->res;
	}

	public function setRes($res): static
	{
		$this->res = $res;
		return $this;
	}


	public function getCode(): int
	{
		return $this->code ?? 200;
	}

	public function setCode(int $code = 200): static
	{
		$this->code = $code;
		return $this;
	}

	public function saveEntity(EntityManagerInterface $entityManaegerInterface, $entity, bool $isUpdate = false): void
	{
		if (!$isUpdate) {
			$entityManaegerInterface->persist($entity);
		}
		$entityManaegerInterface->flush($entity);
	}

	public function removeEntity(EntityManagerInterface $entityManaegerInterface, $entity): void
	{
		$entityManaegerInterface->remove($entity);
		$entityManaegerInterface->flush($entity);
	}

	public function handleFormValidation(ValidatorInterface $validator, string $type): object
	{
		$request = $this->getRequest();
		$form = $this->createForm($type);
		$entity = $form->handleRequest($request)->getData();
		if ($entity === null) {
			$payload = json_decode($request->getContent(), true);
			$now = new DateTimeImmutable();
			if(isset($payload['created_at']) && !$payload['created_at']){
				$payload['created_at'] = $now->format(self::FORMAT_DATE);
			}
			if(isset($payload['updated_at']) && !$payload['updated_at']){
				$payload['updated_at'] = $now->format(self::FORMAT_DATE);
			}
			$form->submit($payload);
			$entity = $form->handleRequest($request)->getData();
			if ($entity === null) {
				throw new \RuntimeException('The form did not return a valid entity.');
			}
			if (isset($payload['id']) && $payload['id']) {
				$entity->setId($payload['id']);
			}
		}
		$this->handleFormError($validator, $entity);
		return $entity;
	}

	/**
	 * Si no es un usario admin, se genera una exception de la clase UnauthorizedException.
	 * @throws UnauthorizedException
	 */
	public function isAdmin(): void
	{
		$method = 'isAdmin';
		if (!$this->getUser()->$method()) {
			throw new UnauthorizedException("No tiene autorización");
		}
	}

	/**
	 * Se encarga de procesar la entidad para poblarla de los datos obtenidos del payload.
	 * @throws FormException The entity identifier was not defined.
	 */
	public function populateEntity(ValidatorInterface $validatorInterface, string $type, $oldEntity = null): object
	{
		$entity = $this->handleFormValidation($validatorInterface, $type);
		$code = Response::HTTP_CREATED;
		if ($this->getRequest()->getMethod() === Request::METHOD_PUT) {
			if (!$oldEntity) {
				throw new FormException("The entity identifier was not defined.");
			}
			$publicKeys = get_object_vars($oldEntity);
			foreach ($publicKeys as $key => $value) {
				$key = self::convertToCamelCase($key);
				$setMethod = "set$key";
				$getMethod = "get$key";
				$isDate = "updatedat" === strtolower($key);
				$value = $isDate ? new DateTimeImmutable() : $entity->$getMethod();
				if('password' === strtolower($key) && $value){
					$value = AuthenticationService::encrypPassword($value);
				}
				$oldEntity->$setMethod($value);
			}
			$entity = $oldEntity;
			$code = Response::HTTP_OK;
		}
		$code = Response::HTTP_OK;
		$this->setCode($code);
		return $entity;
	}

	public function handleDataToJsonResponse(array $entities)
	{
		return ['data' => array_map(function ($entity) {
			return $entity->getData();
		}, $entities)];
	}

	static function convertToCamelCase($string)
	{
		$result = str_replace('_', ' ', $string);
		$result = ucwords($result);
		$result = str_replace(' ', '', $result);
		return ucfirst($result);
	}

	public function createFormView(string $type, $entity = null): FormView
	{
		$form = $this->createForm($type, $entity);
		return $form->createView();
	}

	/**
	 * @throws FormException Form errors.
	 */
	private function handleFormError(ValidatorInterface $validator, $entity): void
	{
		$violations = $validator->validate($entity);
		$errors = [];
		if (count($violations) > 0) {
			foreach ($violations as $error) {
				$errors[$error->getPropertyPath ?? 'default-error'] = $error->getMessage();
			}
			throw new FormException('Verifique la información del formulario.', $errors, 400);
		}
	}
}
