<?php

namespace App\Service;

use App\Exception\UnauthorizedException;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductService extends AbstractService
{
	private ProductRepository $productRepository;
	public function __construct(ProductRepository $productRepository)
	{
		$this->productRepository = $productRepository;
	}

	public function getProperties(): array
	{
		return [
			["Productos", 'productos', 'Agrega producto', 'table-products'],
			['#', 'Nombre', 'Descripción', 'Categoria', 'Precio', 'Existencia', 'Acciones'],
			'new-product'
		];
	}

	public function all(): array
	{
		return ['data' => $this->productRepository->findAll()];
	}

	public function save(): void
	{
		$method = 'isAdmin';
		if(!$this->getUser()->$method()){
			throw new UnauthorizedException("No tiene autorización");
		}
		$entity = $this->handleFormValidation(ProductType::class);
		$code = Response::HTTP_CREATED;
		if($this->getRequest()->getMethod() === Request::METHOD_PUT){
			$code = Response::HTTP_OK;
		}
		$this->productRepository->flush($entity);
		$this->setResponse(null);
		$this->setCode($code);
	}
	
	public function delete(): void
	{

	}

	private function create(): void
	{

	}

	private function update(): void
	{

	}

}
