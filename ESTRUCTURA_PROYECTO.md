# Estructura del Proyecto Laravel

Este documento explica la función de cada directorio y archivo principal dentro del proyecto.

### Directorios Principales (Raíz)

*   **`app/`**: El corazón de la aplicación Laravel. Contiene toda la lógica de negocio, modelos, controladores, etc.
*   **`docker/`**: Contiene los archivos de configuración para el entorno de Docker (`Dockerfile` para PHP, configuración de Nginx).
*   **`node_modules/`**: Almacena las dependencias de JavaScript/Node.js gestionadas por `npm`.
*   **`.git/`**: Directorio oculto de Git para el control de versiones.
*   **`docker-compose.yml`**: Orquesta los servicios de Docker (servidor web, PHP, base de datos).
*   **`package.json`**: Define las dependencias y scripts de Node.js para el frontend.

---

### Dentro de `app/` (Directorio de la Aplicación Laravel)

*   **`app/` (subdirectorio)**:
    *   `Http/Controllers`: Procesan las solicitudes del usuario.
    *   `Models`: Representan las tablas de la base de datos y la interacción con ellas.
    *   `Providers`: Registran y configuran servicios y funcionalidades.
    *   `Mail`: Clases para el envío de correos electrónicos.
    *   `Rules`: Reglas de validación personalizadas.
*   **`bootstrap/`**: Scripts para el arranque del framework y la caché de optimización.
*   **`config/`**: Todos los archivos de configuración de la aplicación (base de datos, correo, etc.).
*   **`database/`**:
    *   `migrations`: Versionado de la estructura de la base de datos.
    *   `seeders`: Clases para poblar la base de datos con datos.
    *   `factories`: "Fábricas" para generar datos de prueba.
*   **`public/`**: Directorio raíz del servidor web. Contiene el `index.php` y los assets compilados (CSS, JS).
*   **`resources/`**:
    *   `views`: Plantillas HTML de la aplicación (`.blade.php`).
    *   `js` y `sass`: Archivos JavaScript y SASS sin compilar.
    *   `lang`: Archivos de traducción.
*   **`routes/`**: Definición de todas las URLs de la aplicación.
    *   `web.php`: Rutas para el navegador.
    *   `api.php`: Rutas para la API.
*   **`storage/`**: Almacenamiento de logs, cachés, sesiones y archivos subidos.
*   **`tests/`**: Pruebas automatizadas.
*   **`vendor/`**: Dependencias PHP gestionadas por Composer, incluyendo el propio Laravel.
*   **`artisan`**: La interfaz de línea de comandos de Laravel.
*   **`.env.example`**: Plantilla para el archivo de configuración de entorno `.env`.
