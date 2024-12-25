<?php

namespace App\Service;

class SalesService extends AbstractService
{
	public function getProperties(): array
	{
		return [
			['Ventas', 'ventas', 'Agregar venta'],
			['#', 'Total', 'Fecha de venta', 'Forma de pago', 'Usuario', 'Acciones'],
			'new-sale'
		];
	}
}
