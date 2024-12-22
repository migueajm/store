migueajmstore/
│
├── assets/                    # Archivos estáticos como CSS, JS, imágenes
│   ├── css/
│   ├── js/
│   └── images/
│
├── bin/                        # Archivos ejecutables, como los comandos de Symfony
│
├── config/                     # Configuración del proyecto
│   ├── packages/               # Configuración de servicios, seguridad, etc.
│   ├── routes/                 # Definición de rutas
│   ├── services.yaml           # Configuración de servicios
│   └── ...                     # Otros archivos de configuración
│
├── node_modules/               # Dependencias de NPM (si usas frontend moderno)
│
├── public/                     # Archivos públicos accesibles por el navegador
│   ├── index.php               # Archivo principal de entrada
│   └── build/                  # Archivos generados por Webpack (si usas Webpack Encore)
│
├── src/                        # Código fuente del proyecto
│   ├── Controller/             # Controladores de las páginas o APIs
│   │   ├── AdminController.php
│   │   ├── ProductController.php
│   │   └── ...                 # Otros controladores
│   │
│   ├── Entity/                 # Entidades de la base de datos
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   └── ...                 # Otras entidades
│   │
│   ├── Form/                   # Formularios (validaciones, entradas)
│   │   ├── ProductType.php
│   │   ├── CategoryType.php
│   │   └── ...                 # Otros formularios
│   │
│   ├── Repository/             # Repositorios personalizados
│   │   ├── ProductRepository.php
│   │   ├── CategoryRepository.php
│   │   └── ...                 # Otros repositorios
│   │
│   ├── Service/                # Servicios personalizados (lógica de negocio)
│   │   ├── ProductService.php
│   │   ├── SaleService.php
│   │   └── ...                 # Otros servicios
│   │
│   └── Security/               # Autenticación y autorización
│       ├── LoginAuthenticator.php
│       └── ...                 # Otros archivos de seguridad
│
├── templates/                  # Plantillas Twig
│   ├── base.html.twig          # Plantilla base para todo el sitio
│   ├── product/                # Plantillas específicas para productos
│   │   ├── index.html.twig
│   │   └── show.html.twig
│   │
│   ├── sale/                   # Plantillas específicas para ventas
│   │   ├── index.html.twig
│   │   └── checkout.html.twig
│   │
│   └── ...                     # Otras plantillas
│
├── translations/               # Archivos de traducción (para multi-idioma)
│   └── messages.es.yaml
│
├── var/                        # Archivos temporales generados por el sistema
│   ├── cache/                  # Archivos de caché
│   ├── log/                    # Archivos de log
│   └── sessions/               # Archivos de sesión
│
├── vendor/                     # Dependencias de Composer (no modificar)
│
├── .env                        # Variables de entorno
├── composer.json               # Dependencias de Composer
├── package.json                # Dependencias de NPM
└── symfony.lock                # Bloqueo de dependencias de Symfony
