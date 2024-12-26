<?php

namespace App\Service;

class SalesService extends AbstractService
{
	public function getProperties(): array
	{
		return [
			['Ventas', 'ventas', 'Agregar venta', 'table-sales'],
			['#', 'Total', 'Fecha de venta', 'Forma de pago', 'Usuario', 'Acciones'],
			'new-sale'
		];
	}
}
