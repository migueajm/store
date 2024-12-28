<?php

namespace App\Service;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
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
		$formHtml = $this->renderView('components/form.html.twig', [
			'form' => $this->createFormView(CategoryType::class, new Category())
		]);
		return [
			["Categorias", 'categorias', 'Agrega categoria', 'table-category'],
			['#', 'Nombre', 'Descripción', 'Creado', 'Modificado', 'Acciones'],
			'new-category',
			'modal' => [
				"id" => 'modal-form-category',
				"size" => 'md',
				"title" => 'Información de la categoria',
				"body" => $formHtml,
				"close" => 'Cancelar',
				"action" => [
						"id" => 'btn-save-category',
						"text" => 'Guardar'
				]
			]
		];
	}

	public function all(): array
	{
    return $this->handleDataToJsonResponse($this->categoryRepository->findAll());
	}

	public function save(ValidatorInterface $validatorInterface, string $type, ?Category $category = null): void
	{
		$this->isAdmin();
		$entity = $this->populateEntity($validatorInterface, $type, $category);
		$this->saveEntity($this->entityManagerInterface, $entity);
	}
	
	public function delete(Category $category): void
	{
		$this->isAdmin();
		$this->removeEntity($this->entityManagerInterface, $category);
	}
}
