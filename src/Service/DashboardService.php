<?php

namespace App\Service;

class DashboardService extends AbstractService
{
	const MODULES = [
		['name' => 'Productos', 'path' => 'app_product_index'],
		['name' => 'Ventas', 'path' => 'app_sales_index'],
		['name' => 'Inventario', 'path' => 'app_stocktaking_index'],
		['name' => 'Reportes', 'path' => 'app_report_index'],
		['name' => 'Cerrar sesión', 'path' => 'app_authentication_sign_out']
	];
}