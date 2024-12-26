<?php

namespace App\Service;

use App\Entity\Category;
use App\Exception\FormException;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryService extends AbstractService
{
	private EntityManagerInterface $entityManagerInterface;
	private CategoryRepository $categoryRepository;
	public function __construct(EntityManagerInterface $entityManagerInterface)
	{
		$this->entityManagerInterface = $entityManagerInterface;
		$this->categoryRepository = $entityManagerInterface->getRepository(Category::class);
	}

	public function getProperties(): array
	{
		return [
			["Categorias", 'categorias', 'Agrega categoria', 'table-category'],
			['#', 'Nombre', 'Descripción', 'Creado', 'Modificado', 'Acciones'],
			'new-category'
		];
	}

	public function all(): array
	{
		return ['data' => $this->categoryRepository->findAll()];
	}

	public function save(ValidatorInterface $validatorInterface,?Category $category = null): void
	{
		$this->isAdmin();
		$entity = $this->handleFormValidation($validatorInterface, CategoryType::class);
		$code = Response::HTTP_CREATED;
		if($this->getRequest()->getMethod() === Request::METHOD_PUT){
			if(!$category){
				throw new FormException("No se definio el identificar de la categoria.");
			}
			$category->setName($entity->getName());
			$category->setDescription($entity->getDescription());
			$category->setUpdatedAt(new DateTimeImmutable());
			$entity = $category;
			$code = Response::HTTP_OK;
		}
		$this->saveEntity($this->entityManagerInterface, $entity);
		$this->setCode($code);
	}
	
	public function delete(Category $category): void
	{
		$this->isAdmin();
		$this->removeEntity($this->entityManagerInterface, $category);
	}
}
