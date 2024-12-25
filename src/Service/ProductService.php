<?php

namespace App\Service;

class ProductService extends AbstractService
{
	public function getProperties(): array
	{
		return [
			["Productos", 'productos', 'Agrega producto'],
			['#', 'Nombre', 'Descripción', 'Categoria', 'Precio', 'Stock', 'Acciones'],
			'new-product'
		];
	}
}
