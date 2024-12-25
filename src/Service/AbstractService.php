<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class AbstractService extends AbstractController
{
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

	public function getModuleAndTableProperties(array $text, array $head, string $action): array
	{
		$title = $text[0];
		$module = [
			'title' => "Gestión de {$text[1]}",
			'id' => "btn-$action",
			'action' => $text[2],
			'href' => "#{{$action}}"
		];
		$table = [
			'head' => $head,
			'body' => "No hay {$text[1]} registrados",
			'count' => count($head)
		];
		return compact('title', 'module', 'table');
	}
}
