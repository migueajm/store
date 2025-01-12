# Punto de venta

Este proyecto es una aplicación desarrollada con el framework Symfony. Este documento detalla los pasos necesarios para instalar, configurar y ejecutar el proyecto en un entorno local.
---
## Requisitos previos

Asegúrate de que tu sistema cumpla con los siguientes requisitos antes de comenzar:

- PHP >= 8.2 (recomendado)
- Composer
- Servidor web (Apache/Nginx) o el servidor embebido de Symfony
- Extensiones de PHP necesarias:
  - sqlite3
  - pdo_sqlite
  - intl
  - mbstring
---

## Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/migueajm/store.git
   cd store
	 ```
2. **Instalar dependencias**
   ```bash
   composer install
	 ```
3. **Configurar las variables de entorno(archivo de referencia ".env.example")**
4. **Configurar la base de datos**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
	 ```
5. **Ejecución del proyecto**
	- Usando el servidor embebido de Symfony.
	- Ejecuta el siguiente comando para iniciar el servidor embebido:
   ```bash
   php bin/console server:run
	 ```
## Contribución

Si deseas contribuir a este proyecto, por favor abre un issue o crea un pull request con tus cambios.