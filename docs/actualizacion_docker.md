# Guía de Actualización del Proyecto en Servidor Docker (Facultad)

Esta guía detalla los pasos exactos para actualizar la aplicación en el entorno Docker de la facultad para reflejar los últimos cambios implementados:
- **Gestión de Plantillas de Correo Electrónico:** CRUD, vista previa modal y renderizado dinámico en notificaciones de reservas.
- **Equipo de Trabajo:** ABM completo en el panel administrativo, creación automática de usuarios operadores con rol `OPERADOR` y vinculación a la dependencia del Observatorio.
- **Instalaciones y Equipamiento:** ABM completo en el panel administrativo con soporte de imágenes y viñetas de características.
- **Rediseño Frontend:** Vista pública de `/equipo` (con avatares interactivos y cuadrícula balanceada) y `/instalaciones` (con diseño alternado de imágenes).
- **Métricas y Estadísticas del Observatorio:** Dashboard interactivo adaptado con KPIs de visitantes reales, afluencia escolar, modalidades y gráficos de demanda.

---

## 🚀 Comando Rápido (Todo en uno)

Si estás en la raíz del proyecto en el servidor de la facultad, puedes ejecutar este bloque completo en la terminal:

```bash
git pull origin Develop && \
docker exec turnos_app composer dump-autoload && \
docker exec turnos_app php artisan migrate --force && \
docker exec turnos_app php artisan db:seed --class=PlantillasEmailSeeder --force && \
docker exec turnos_app php artisan db:seed --class=EquipoAndInstalacionesSeeder --force && \
docker exec turnos_app php artisan view:clear && \
docker exec turnos_app php artisan route:clear && \
docker exec turnos_app php artisan config:clear && \
docker exec turnos_app php artisan cache:clear && \
docker exec turnos_app mkdir -p public/uploads/equipo public/uploads/instalaciones && \
docker exec turnos_app chown -R www-data:www-data storage bootstrap/cache public/uploads && \
docker exec turnos_app chmod -R 775 storage bootstrap/cache public/uploads
```

---

## 📋 Pasos Detallados

### 1. Descargar los últimos cambios del repositorio
Navega a la carpeta del proyecto en el servidor y baja la rama `Develop`:

```bash
cd /ruta/hacia/Turnos
git pull origin Develop
```

### 2. Regenerar el Autoloader de Composer
Como se agregaron nuevos modelos (`MiembroEquipo`, `Instalacion`, `PlantillaEmail`), controladores y seeders, PHP necesita reconstruir su mapa de clases:

```bash
docker exec turnos_app composer dump-autoload
```

### 3. Ejecutar las nuevas migraciones de Base de Datos
Crea las tablas `plantillas_email`, `equipo_miembros` e `instalaciones`:

```bash
docker exec turnos_app php artisan migrate --force
```

### 4. Ejecutar los Seeders de datos y usuarios
Carga los contenidos por defecto, crea las cuentas de usuario operador para el equipo y asocia sus permisos y dependencias:

```bash
# 1. Seed de plantillas de correo
docker exec turnos_app php artisan db:seed --class=PlantillasEmailSeeder --force

# 2. Seed de integrantes, usuarios operadores e instalaciones
docker exec turnos_app php artisan db:seed --class=EquipoAndInstalacionesSeeder --force
```

### 5. Limpieza de cachés de Laravel
Asegura que el contenedor compile las nuevas plantillas Blade y registre las nuevas rutas:

```bash
docker exec turnos_app php artisan view:clear
docker exec turnos_app php artisan route:clear
docker exec turnos_app php artisan config:clear
docker exec turnos_app php artisan cache:clear
```

### 6. Permisos de subida de archivos (Imágenes y Fotos)
Garantiza que el servidor web (`www-data`) tenga permisos de escritura en las carpetas públicas de subida:

```bash
docker exec turnos_app mkdir -p public/uploads/equipo public/uploads/instalaciones
docker exec turnos_app chown -R www-data:www-data storage bootstrap/cache public/uploads
docker exec turnos_app chmod -R 775 storage bootstrap/cache public/uploads
```

---

## 🔑 Credenciales para la Demostración

| Usuario / Integrante | Correo Electrónico | Contraseña | Rol Asignado |
| :--- | :--- | :--- | :--- |
| **Administrador General** | `admin@admin.com` | `123456` | `ADMINISTRADOR` |
| **Carlos Martínez (Responsable)** | `carlos.martinez@unsa.edu.ar` | `123456` | `OPERADOR` |
| **María José Gómez (Colaborador)** | `mjgomez@unsa.edu.ar` | `123456` | `OPERADOR` |
| **Marcos Martin (Colaborador)** | `mmartin@unsa.edu.ar` | `123456` | `OPERADOR` |
| *(Demás colaboradores)* | `[inicial+apellido]@unsa.edu.ar` | `123456` | `OPERADOR` |

*Todos los usuarios operadores tienen automáticamente asignada la **Dependencia 27 (OBSERVATORIO)**.*

---

## 🎯 Puntos Clave para la Demostración

### 1. Portal Público del Observatorio
- **Ruta `/equipo`:** Muestra a los docentes responsables destacados con tarjetas personalizadas y la nueva grilla responsiva de colaboradores con avatares circulares interactivos.
- **Ruta `/instalaciones`:** Muestra la Cúpula Hemisférica, el Telescopio Reflector y el Instrumental Móvil con disposición alternada de imágenes y viñetas dinámicas.

### 2. Panel Administrativo (Sidebar)
- **Trámites y Servicios > Equipo de Trabajo (`/equipo-trabajo`):**
  - Tabla con foto, rol, usuario vinculado del sistema, orden y estado.
  - Filtros rápidos por *Docentes Responsables* y *Colaboradores*.
  - Formulario de alta/edición con opción de asociar a un usuario del sistema (Many2one opcional) o dar de alta colaboradores externos.
- **Trámites y Servicios > Instalaciones (`/instalaciones-gestion`):**
  - ABM de infraestructura con selección de íconos FontAwesome, subida de fotos y desglose de viñetas en líneas separadas.
- **Configuración General > Plantillas de Correo (`/plantillas-email`):**
  - Editor de plantillas de confirmación y cancelación para reservas individuales e institucionales.
  - Botón de **Vista Previa** interactiva para previsualizar el correo final renderizado.
