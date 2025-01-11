<?php

namespace App\Repository;

use App\Entity\SaleDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SaleDetail>
 */
class SaleDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaleDetail::class);
    }

    /**
     * Obtiene el un detalle de venta de acuerdo a un producto y una venta.
     *
     * @param int $product ID del producto.
     * @param int $sale ID de la venta.
     */
    public function findOneByProductAndSale(int $product, int $sale): ?SaleDetail
    {
        return $this->findOneBy(compact('product', 'sale'));
    }

    /**
     * Obtiene el total de todos los detalles de una venta.
     *
     * @param int $saleId ID de la venta.
     * @return string|null Total de la venta en formato decimal, o null si no hay detalles.
     */
    public function getTotalBySale(int $saleId): float
    {
        $qb = $this->createQueryBuilder('sd');

        $total = $qb->select('SUM(sd.total_price) as total')
            ->where('sd.sale = :saleId')
            ->setParameter('saleId', $saleId)
            ->getQuery()
            ->getSingleScalarResult();
        return (float) ($total ?? 0.0);
    }
}
