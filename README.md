## **PORTAL EMPLEADOS API BACKEND **

Aplicacion para el manejo de apis que permiten el manejo de los recibos de ARSAT

## Requerimientos
- Poseer una base de datos MySQL/MariaDB.
- Poseer instalado el gestor de paquetes "Composer".

## Instalación
- Clonar el repositorio en un directorio local.
- Crear un archivo .env en el directorio raíz del proyecto, con el contenido del archivo .env.example.
- Configurar la conexión de la base de datos al entorno local editando el archivo .env, indicando el nombre de la base de datos en el campo DB_DATABASE, usuario en DB_USERNAME y contraseña en DB_PASSWORD.
- Ejecutar el comando "composer install" para realizar la instalación de las dependencias del proyecto.
- Ejecutar el comando "php artisan migrate" para generar las tablas y relaciones de las mismas en la base de datos indicada en el archivo .env.
- Ejecutar el comando "php artisan db:seed" para realizar el seeding de las tablas.
- En este punto, se puede montar el servidor de laravel con el comando "php artisan serve", el cual escuchará en el puerto 8000 del servidor local (http://localhost:8000).
