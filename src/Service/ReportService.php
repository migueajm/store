<?php

namespace App\Service;

class ReportService extends AbstractService
{
	public function getProperties(): array
	{
		return [
			["Reportes", 'reportes', 'Generar reporte'],
			['#', 'Nombre', 'Descripción', 'Categoria', 'Precio', 'Stock', 'Acciones'],
			'generate-report'
		];
	}
}
