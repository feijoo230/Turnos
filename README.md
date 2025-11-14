# sistema de turnos

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Nginx](https://img.shields.io/badge/Nginx-009639?style=for-the-badge&logo=nginx&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-4169E1?style=for-the-badge&logo=postgresql&logoColor=white)
![Node.js](https://img.shields.io/badge/Node.js-339933?style=for-the-badge&logo=nodedotjs&logoColor=white)
![SASS](https://img.shields.io/badge/Sass-CC6699?style=for-the-badge&logo=sass&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![jQuery](https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white)

---

## 🚀 GUÍA DE INSTALACIÓN Y USO (LARAVEL + DOCKER)

Esta guía te ayudará a configurar y ejecutar el proyecto en tu entorno de desarrollo local.

### ✅ Pre-requisitos

Asegúrate de tener instalados y configurados en tu sistema:

1.  **Git**: Para clonar repositorios y control de versiones.
    - [Instalar Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
2.  **Docker & Docker Compose**: Para la gestión de contenedores.
    - [Instalar Docker](https://docs.docker.com/get-docker/)
3.  **PHP (>=8.0)**: Necesario para Composer.
    - [Instalar PHP](https://www.php.net/manual/es/install.php)
4.  **Composer**: Gestor de dependencias para PHP.
    - [Instalar Composer](https://getcomposer.org/download/)
5.  **Node.js (>=14) y npm**: Para dependencias de frontend.
    - [Instalar Node.js y npm](https://nodejs.org/en/download/)

---

### 🛠️ Pasos de Instalación

#### 1. Clonar el Repositorio
```bash
git clone <URL_DEL_REPOSITORIO> turnos
cd turnos
```

#### 2. Instalar Dependencias de Composer
Navega a la carpeta `app` y ejecuta:
```bash
cd app
composer install
```

#### 3. Instalar Dependencias de Node.js
En la misma carpeta `app`:
```bash
npm install
```
*(Nota: Se recomienda `npm install` sobre `npm ci` para la primera instalación para generar el `package-lock.json` si es necesario).*

#### 4. Configurar el Archivo de Entorno (`.env`)
Dentro de la carpeta `app`, copia el archivo de ejemplo:
```bash
cp .env.example .env
```
Asegúrate de que las variables de base de datos coincidan con las definidas en `docker-compose.yml`.

#### 5. Construir e Iniciar los Contenedores
Vuelve a la raíz del proyecto y ejecuta:
```bash
docker-compose build
docker-compose up -d
```

#### 6. Configurar Laravel
Ejecuta los siguientes comandos para finalizar la configuración de la aplicación dentro del contenedor:

*   **Generar Clave de Aplicación:**
    ```bash
    docker-compose exec app php artisan key:generate
    ```
*   **Ejecutar Migraciones y Seeders:**
    ```bash
    docker-compose exec app php artisan migrate --seed
    ```

#### 7. Compilar Assets de Frontend
```bash
docker-compose exec app npm run dev
```

---

### 🎉 ¡Listo!

Ahora puedes acceder a la aplicación en [http://localhost:8083](http://localhost:8083) (o el puerto que hayas configurado en `docker-compose.yml` para Nginx).

### 🐛 Solución de Problemas

- **Errores de conexión a la base de datos:** Reinicia los contenedores (`docker-compose restart`) y verifica que las credenciales en `/app/.env` sean correctas.
- **Permisos:** Si encuentras errores de permisos, ejecuta los siguientes comandos:
  ```bash
  docker-compose exec app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
  docker-compose exec app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
  ```
