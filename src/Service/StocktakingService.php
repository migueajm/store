<?php

namespace App\Service;

use App\Entity\InventoryMovement;
use App\Entity\Product;
use App\Repository\InventoryMovementRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class StocktakingService extends AbstractService
{
	private EntityManagerInterface $entityManagerInterface;
	private ProductRepository $productRepository;
	private InventoryMovementRepository $inventoryRepository;
	public function __construct(EntityManagerInterface $entityManagerInterface)
	{
		$this->entityManagerInterface = $entityManagerInterface;
		$this->productRepository = $this->entityManagerInterface->getRepository(Product::class);
		$this->inventoryRepository = $this->entityManagerInterface->getRepository(InventoryMovement::class);
	}

	public function getProperties(bool $isAdmin = false): array
	{
		$table = 'table-product';
		$tableHead = ['#', 'Producto', 'Existencia', 'Precio'];
		$action = 'stocktaking-history';
		$actionText = "Ver movimientos";
		if($isAdmin){
			$table = 'table-stocktaking';
			$tableHead = ['#', 'Producto', 'Cambio', 'Razón', 'Fecha'];
			$action = 'product-stock';
			$actionText = 'Ver existencias';
		}
		return [
			["Inventario", 'inventario',$actionText, $table],
			$tableHead,
			$action,
			[]
		];
	}

	public function all()
	{
		return $this->handleDataToJsonResponse($this->inventoryRepository->findAll());
	}

	public function stockAll()
	{
		return $this->handleDataToJsonResponse($this->productRepository->findAll());
	}
}
