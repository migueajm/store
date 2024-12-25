<?php

namespace App\Service;

class UserService extends AbstractService
{
	public function getProperties(): array
	{
		return [
			["Usuarios", 'usuarios', 'Agrega usuario'],
			['#', 'Nombre', 'Apellido', 'Usuario', 'Rol', 'Alta', 'Acciones'],
			'new-user'
		];
	}
}
