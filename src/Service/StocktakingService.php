<?php

namespace App\Service;

class StocktakingService extends AbstractService
{
	public function getProperties(): array
	{
		return [
			["Inventario", 'inventario', 'Agrega registro', 'table-stocktaking'],
			['#', 'Producto', 'Cambio', 'Razón', 'Fecha', 'Acciones'],
			'new-stocktaking'
		];
	}
}
