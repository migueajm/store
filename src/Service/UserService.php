<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService extends AbstractService
{
	private UserRepository $userRepository;
	private EntityManagerInterface $entityManagerInterface;

	public function __construct(EntityManagerInterface $entityManagerInterface)
	{
		$this->entityManagerInterface = $entityManagerInterface;
		$this->userRepository = $entityManagerInterface->getRepository(User::class);
	}

	public function getProperties(): array
	{
		return [
			["Usuarios", 'usuarios', 'Agrega usuario', 'table-users'],
			['#', 'Nombre', 'Apellido', 'Usuario', 'Rol', 'Acciones'],
			'new-user'
		];
	}

	public function all(): array
	{
		$user = $this->getUser();
		$method = "getId";
		$id = $user->$method();
    return $this->handleDataToJsonResponse($this->userRepository->findAllExceptCurrentUser($id));
	}

	public function save(ValidatorInterface $validatorInterface, string $type, ?User $user = null): void
	{
		$this->isAdmin();
		$entity = $this->populateEntity($validatorInterface, $type, $user);
		$this->saveEntity($this->entityManagerInterface, $entity);
	}
	
	public function delete(User $user): void
	{
		$this->isAdmin();
		$this->removeEntity($this->entityManagerInterface, $user);
	}
}
