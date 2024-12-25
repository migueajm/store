<?php

namespace App\Service;

class StocktakingService extends AbstractService
{
	public function getProperties(): array
	{
		return [
			["Inventario", 'inventario', 'Agrega'],
			['#', 'Producto', 'Cambio', 'Razón', 'Fecha', 'Acciones'],
			'new-stocktaking'
		];
	}
}
