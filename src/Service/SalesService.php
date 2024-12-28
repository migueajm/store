<?php

namespace App\Service;

use App\Entity\Sale;
use App\Entity\SaleDetail;
use App\Entity\User;
use App\Form\SaleDetailType;
use App\Form\SaleType;
use App\Repository\SaleDetailRepository;
use App\Repository\SaleRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SalesService extends AbstractService
{
	private EntityManagerInterface $entityManagerInterface;
	private SaleRepository $saleRepository;
	private SaleDetailRepository $saleDetailRepository;
	public function __construct(EntityManagerInterface $entityManagerInterface)
	{
		$this->entityManagerInterface = $entityManagerInterface;
		$this->saleRepository = $entityManagerInterface->getRepository(Sale::class);
		$this->saleDetailRepository = $entityManagerInterface->getRepository(SaleDetail::class);
	}

	public function getProperties(): array
	{
		$formHtml = $this->renderView('components/form.html.twig', [
			'form' => $this->createFormView(SaleType::class, new Sale())
		]);
		return [
			['Ventas', 'ventas', 'Agregar venta', 'table-sales'],
			['#', 'Total', 'Fecha de venta', 'Forma de pago', 'Usuario', 'Acciones'],
			'new-sale',
			'modal' => [
				'id' => 'modal-form-sale',
				'size' => 'xl',
				'title' => 'Información de la venta',
				'body' => $formHtml,
				'close' => 'Cancelar',
				'action' => [
						'id' => 'btn-save-sale',
						'text' => 'Guardar',
				],
			]
		];
	}

	public function getTableProductProperties(): array
	{
		return [
			'id' => 'table-detail-sale',
			'head' => ['Producto', 'Cantidad', 'Precio', 'Total', 'Acciones'],
			'count' => 5,
			'body' => 'Sin productos registrados'
		];
	}

	public function all(): array
	{
    return $this->handleDataToJsonResponse($this->saleRepository->findAll());
	}

	public function save(ValidatorInterface $validatorInterface, string $type, ?Sale $category = null): void
	{
		$entity = $this->populateEntity($validatorInterface, $type, $category);
		$this->saveEntity($this->entityManagerInterface, $entity);
	}
	
	public function delete(Sale $category): void
	{
		$this->isAdmin();
		$this->removeEntity($this->entityManagerInterface, $category);
	}

	public function generate(): int
	{
		$method = 'getId';
		$user = $this->entityManagerInterface->getRepository(User::class)->find($this->getUser()->$method());
		$sale = new Sale();
		$sale->setTotalAmount('0.0')
			->setPaymentMethod('Efectivo')
			->setSaleDate(new DateTimeImmutable())
			->setUser($user);
		$this->entityManagerInterface->persist($sale);
		$this->entityManagerInterface->flush();
		return $sale->getId();
	}

	public function findDetailBySaleId(int $sale): array
	{
		$details = $this->saleDetailRepository->findBy(compact('sale'));
		return $this->handleDataToJsonResponse($details);
	}

	public function saveDetail(ValidatorInterface $validatorInterface, ?SaleDetail $saleDetail)
	{
		$entity = $this->populateEntity($validatorInterface, SaleDetailType::class, $saleDetail);
		$this->saveEntity($this->entityManagerInterface, $entity);
	}
}
