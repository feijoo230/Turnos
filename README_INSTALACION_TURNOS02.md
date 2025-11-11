# GUÍA DE INSTALACIÓN Y USO DEL PROYECTO TURNOS02 (LARAVEL + DOCKER)

## Pre-requisitos

Asegúrate de tener instalados y configurados en tu sistema:

1. **Git**: Para clonar repositorios y control de versiones.
   - [Instalar Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
2. **Docker**: Para contenedores.
   - [Instalar Docker](https://docs.docker.com/get-docker/)
3. **Docker Compose**: Para orquestar los contenedores.
   - [Instalar Docker Compose](https://docs.docker.com/compose/install/)
4. **PHP (>=8.0)**: Necesario para Composer.
   - [Instalar PHP](https://www.php.net/manual/es/install.php)
5. **Composer**: Gestor de dependencias PHP.
   - [Instalar Composer](https://getcomposer.org/download/)
6. **Node.js (>=14) y npm**: Para dependencias JS.
   - [Instalar Node.js y npm](https://nodejs.org/en/download/)

---

## Pasos de Instalación

### 1. Estructura de Carpetas

```
proyectos/turnos02/
├── docker-compose.yml
├── app/
│   ├── ... (código Laravel)
├── docker/
│   ├── nginx/
│   │   └── default.conf
│   └── php/
│       └── Dockerfile
```

### 2. Clonar el repositorio (si aplica)

```sh
git clone git@gitlab.com:desarrolladores-rectorado/turnos02.git turnos02
cd turnos02
```

### 3. Instalar dependencias de Composer (dentro de `/app`)

```sh
cd app
composer install
```

### 4. Instalar dependencias de Node.js (dentro de `/app`)

```sh
npm ci
```

### 5. Configurar el archivo de entorno `.env` en `/app`

Si no existe, copia desde el ejemplo:

```sh
cp .env.example .env
```

Asegúrate de que las variables de base de datos coincidan con las del `docker-compose.yml`:

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel_user
DB_PASSWORD=secret
```

### 6. Construir e iniciar los contenedores

Desde la raíz del proyecto:

```sh
docker-compose build
docker-compose up -d
```

### 7. Otorgar permisos a Laravel (dentro del contenedor `app`)

```sh
docker-compose exec app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
```

### 8. Generar clave de aplicación Laravel

```sh
docker-compose exec app php artisan key:generate
```

### 9. Instalar cliente MySQL en el contenedor (opcional, para debug)

```sh
docker exec -it turnos_app bash
apt-get update && apt-get install -y default-mysql-client
```

### 10. Ejecutar migraciones y seeders

```sh
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

Para un seeder específico:

```sh
docker-compose exec app php artisan db:seed --class=NombreDelSeeder
```

### 11. Compilar assets frontend (dentro del contenedor `app`)

```sh
docker-compose exec app npm run dev
```

---

## Solución de Problemas

- Si tienes errores de conexión a la base de datos, reinicia los contenedores:

```sh
docker-compose restart
```

- Verifica que las variables de entorno en `/app/.env` y `docker-compose.yml` coincidan.

---

¡Listo! Accede a la app en [http://localhost:8083](http://localhost:8083) (o el puerto configurado en nginx).
