# Documentación: Landing Page y Despliegue en Producción
**Observatorio Astronómico Dr. Elvio Alanís**  
Facultad de Ciencias Exactas — Universidad Nacional de Salta (UNSa)

---

## 1. Arquitectura de la Solución (Opción A)

Se implementó la **Opción A**, integrando la Landing Page oficial directamente dentro del proyecto Laravel:
- **Ruta Raíz (`/` o `/inicio`):** Carga la Landing Page astronómica moderna y reactiva (`LandingController@index`).
- **Ruta de Turnos (`/turnos` o `/solicitar-turno`):** Conduce directamente al wizard de reserva de turnos (`TramitesController@index`).
- **Ruta de Consulta (`/turno.buscar`):** Búsqueda directa por DNI o código de reserva sin necesidad de inicio de sesión.
- **Ruta de Acceso Unificado / Mi Portal (`/login`):** Portal de autenticación tanto para visitantes/usuarios particulares como para operadores y administradores (admite correo/contraseña y Google Social Login).
- **Ruta de Mis Turnos (`/mis-turnos`):** Panel privado del visitante donde visualiza sus turnos anteriores, turnos próximos, descarga comprobantes PDF y gestiona integrantes de visitas grupales.
- **Ruta del Panel Administrativo (`/home`):** Panel exclusivo para usuarios con roles `ADMINISTRADOR` u `OPERADOR`. Si un usuario regular (`USUARIO`) intenta ingresar, se le redirige automáticamente a `/mis-turnos`.

---

## 2. Archivos Creados y Modificados

1. **Controladores:**
   - [`app/app/Http/Controllers/frontend/LandingController.php`](file:///c:/Users/feijo/Desktop/Turnos/app/app/Http/Controllers/frontend/LandingController.php): Sirve la landing page.
   - [`app/app/Http/Controllers/Auth/LoginController.php`](file:///c:/Users/feijo/Desktop/Turnos/app/app/Http/Controllers/Auth/LoginController.php): Redirige a los usuarios con rol `USUARIO` directamente a `route('mis-turnos')` tras autenticarse (tanto por login clásico como con Google).
   - [`app/app/Http/Controllers/HomeController.php`](file:///c:/Users/feijo/Desktop/Turnos/app/app/Http/Controllers/HomeController.php): Redirige usuarios sin rol de operador/admin a `route('mis-turnos')`.
2. **Vistas:**
   - [`app/resources/views/frontend/landing.blade.php`](file:///c:/Users/feijo/Desktop/Turnos/app/resources/views/frontend/landing.blade.php): Landing page con navbar adaptativo (botón "Mi Portal" cuando es visitante, y menú dropdown con nombre cuando está autenticado), nueva sección de **Portal del Visitante y Comunidad** (turnos anteriores, próximos, comprobantes, comentarios futuros y facturación), FAQ y diseño cósmico.
   - [`app/resources/views/auth/login.blade.php`](file:///c:/Users/feijo/Desktop/Turnos/app/resources/views/auth/login.blade.php): Actualizado para presentarse como *Portal de Usuarios, Visitantes y Operadores*.
3. **Rutas:**
   - [`app/routes/web.php`](file:///c:/Users/feijo/Desktop/Turnos/app/routes/web.php): Enrutamiento raíz hacia la landing y turnos en `/turnos`.
4. **Directorio de Imágenes:**
   - [`app/public/img/observatorio/`](file:///c:/Users/feijo/Desktop/Turnos/app/public/img/observatorio/): Recursos visuales astronómicos.

---

## 3. Guía: Cómo Modificar y Agregar las Imágenes Reales

Cuando te entreguen las fotos oficiales del Observatorio Alanís, puedes actualizarlas fácilmente siguiendo estos pasos:

### Ubicación de los Archivos
Todas las imágenes de la landing se encuentran en la carpeta:
```
app/public/img/observatorio/
```

### Tabla de Imágenes y Especificaciones

| Archivo | Sección donde se muestra | Resolución Recomendada | Proporción |
| :--- | :--- | :--- | :--- |
| `hero-bg.jpg` | **Fondo del Hero Principal** (portada superior) | `1920 x 1080 px` | 16:9 (Horizontal amplia) |
| `observacion-nocturna.jpg` | **Tarjeta 1: Observaciones Nocturnas** (telescopio/cielo) | `800 x 600 px` | 4:3 |
| `visitas-escolares.jpg` | **Tarjeta 2: Visitas Escolares** (estudiantes/grupos) | `800 x 600 px` | 4:3 |
| `divulgacion.jpg` | **Tarjeta 3: Divulgación y Charlas** (auditorio/evento) | `800 x 600 px` | 4:3 |

### Método 1: Reemplazo Directo de Archivos (Más Rápido)
1. Toma las fotos que te pasen.
2. Renómbralas con el nombre exacto de la tabla (por ejemplo, `hero-bg.jpg`).
3. Cópialas dentro de `app/public/img/observatorio/` sobrescribiendo los archivos existentes.
4. ¡Listo! La landing page cargará automáticamente las nuevas imágenes sin necesidad de tocar código.

### Método 2: Agregar Nuevas Imágenes o Cambiar Nombres
Si deseas usar otros nombres de archivo o agregar más fotos:
1. Guarda tus fotos en `app/public/img/observatorio/` (ej: `telescopio-principal.jpg`).
2. Abre el archivo [`app/resources/views/frontend/landing.blade.php`](file:///c:/Users/feijo/Desktop/Turnos/app/resources/views/frontend/landing.blade.php).
3. Busca el comentario `NOTA DE IMAGEN:` correspondiente en el HTML y actualiza la función `asset()`:
   ```html
   <!-- Ejemplo en la tarjeta de actividades: -->
   <img src="{{ asset('img/observatorio/tu-nueva-foto.jpg') }}" alt="Descripción de la foto" class="activity-img">
   ```
4. Para el fondo del Hero, busca la regla `.hero-section` en el bloque `<style>` y modifica:
   ```css
   url("{{ asset('img/observatorio/tu-nueva-foto-hero.jpg') }}")
   ```

---

## 4. Procedimiento de Despliegue en el Servidor de la UNSa (Usermin / FTPS)

### Servidor y Accesos
- **Dominio:** `observatorioalanis.exa.unsa.edu.ar`
- **Panel Usermin:** `https://usermin.exa.unsa.edu.ar` (usuario: `observatorioalanis`)
- **Servidor FTP (FileZilla):** `cpanel.exa.unsa.edu.ar` (Puerto 21, modo FTPS explícito, usuario: `observatorioalanis`)
- **Base de Datos MariaDB:** Base `observatorioalanis`, usuario `observatorioalanis`.

---

### Paso 1: Respaldo previo en el servidor
1. Conéctate con FileZilla a `cpanel.exa.unsa.edu.ar`.
2. En el servidor verás la carpeta `public_html/` (que actualmente tiene los archivos de WordPress instalados por defecto).
3. Renombra esa carpeta o mueve su contenido a una carpeta de resguardo (por ejemplo `wordpress_backup/`). Crea una carpeta `public_html/` limpia y vacía.

---

### Paso 2: Estructura Segura de Carpetas en el Servidor
En hosting compartido con Apache nunca se debe poner el núcleo de Laravel dentro de `public_html`. La estructura debe ser:

```text
/home/observatorioalanis/
│
├── turnos/                     <-- Aquí subes el proyecto Laravel COMPLETO (excepto public/ y node_modules/)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   └── ...
│
└── public_html/                <-- Aquí subes ÚNICAMENTE el contenido de la carpeta 'public/' de Laravel
    ├── index.php
    ├── .htaccess
    ├── css/
    ├── js/
    ├── img/
    └── favicon.ico
```

---

### Paso 3: Configurar el archivo `public_html/index.php`
Abre el archivo `index.php` que subiste dentro de `public_html/` y verifica que las rutas apunten a la carpeta `turnos`:
```php
require __DIR__.'/../turnos/vendor/autoload.php';

$app = require_once __DIR__.'/../turnos/bootstrap/app.php';
```

---

### Paso 4: Archivo `.env` de Producción
En `/home/observatorioalanis/turnos/.env`, configura los parámetros de producción:
```env
APP_NAME="Observatorio Dr. Elvio Alanís"
APP_ENV=production
APP_KEY=base64:TU_APP_KEY_AQUI
APP_DEBUG=false
APP_URL=https://observatorioalanis.exa.unsa.edu.ar

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=observatorioalanis
DB_USERNAME=observatorioalanis
DB_PASSWORD=MdDHcpwbT7O0sy1kyH44eho0b
```

---

### Paso 5: Permisos de Escritura en el Servidor
En Usermin (File Manager) o por FTP, asegúrate de que las siguientes carpetas tengan permisos de escritura (chmod 775 o 777):
- `/home/observatorioalanis/turnos/storage/` (y sus subcarpetas `logs/`, `framework/views/`, `framework/sessions/`, `framework/cache/`)
- `/home/observatorioalanis/turnos/bootstrap/cache/`

---

### Paso 6: Importar la Base de Datos
1. Exporta tu base de datos local a un archivo `.sql`:
   ```bash
   mysqldump -u root -p turnos > turnos_produccion.sql
   ```
2. Ingresa a **Usermin** -> Menú lateral -> **Applications** -> **MySQL Database**.
3. Selecciona la base de datos `observatorioalanis`.
4. Utiliza la opción **Execute SQL** o **Import** para subir el archivo `.sql` e importar las tablas y registros iniciales.
