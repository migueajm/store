<?php

namespace App\Service;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService extends AbstractService
{
	private EntityManagerInterface $entityManagerInterface;
	private ProductRepository $productRepository;
	public function __construct(EntityManagerInterface $entityManagerInterface)
	{
		$this->entityManagerInterface = $entityManagerInterface;
		$this->productRepository = $entityManagerInterface->getRepository(Product::class);
	}

	public function getProperties(): array
	{
		$formHtml = $this->renderView('components/form.html.twig', [
			'form' => $this->createFormView(ProductType::class, new Product())
		]);
		return [
			["Productos", 'productos', 'Agrega producto', 'table-products'],
			['#', 'Nombre', 'Descripción', 'Categoria', 'Precio', 'Existencia', 'Acciones'],
			'new-product',
			'modal' => [
				'id' => 'modal-form-product',
				'size' => 'xl',
				'title' => 'Información del producto',
				'body' => $formHtml, 
				'close' => 'Cancelar',
				'action' => [
						'id' => 'btn-save-product',
						'text' => 'Guardar',
				],
			]
		];
	}

	public function all(): array
	{
    return $this->handleDataToJsonResponse($this->productRepository->findAll());
	}

	public function save(ValidatorInterface $validatorInterface, string $type, ?Product $category = null): void
	{
		$this->isAdmin();
		$entity = $this->populateEntity($validatorInterface, $type, $category);
		$this->saveEntity($this->entityManagerInterface, $entity);
	}
	
	public function delete(Product $product): void
	{
		$this->isAdmin();
		$this->removeEntity($this->entityManagerInterface, $product);
	}
}
