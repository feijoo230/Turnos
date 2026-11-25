# Guía de Actualización a Producción (FileZilla + Usermin)

**Sistema:** Observatorio Astronómico Prof. Elvio Alanís — UNSa  
**URL Producción:** `https://observatorioalanis.exa.unsa.edu.ar`  

Esta guía documenta paso a paso cómo subir actualizaciones desde el entorno local (`c:\Users\feijo\Desktop\Turnos\app`) al servidor de producción de la Facultad de Ciencias Exactas sin acceso SSH, respetando las restricciones reales del servidor (`ProFTPD AllowOverwrite off`, jaula `chroot` y Linux *case-sensitive*).

---

## 1. Datos de Conexión y Mapeo de Carpetas

- **FileZilla (FTPS explícito sobre TLS, modo Pasivo):**
  - **Servidor:** `cpanel.exa.unsa.edu.ar`
  - **Puerto:** `21`
  - **Usuario:** `observatorioalanis` *(solicitar/usar contraseña configurada)*
- **Panel Usermin (File Manager y MariaDB):**
  - **URL:** `https://usermin.exa.unsa.edu.ar`
  - **Usuario:** `observatorioalanis`

### Equivalencia de rutas (Local $\rightarrow$ Servidor)

| Carpeta en tu PC (Local) | Carpeta en el Servidor (Remoto) | Contenido |
|---|---|---|
| `app/public/<ruta>` | `/home/observatorioalanis/public_html/<ruta>` | Imágenes, CSS, JS, archivos públicos |
| `app/<resto>` | `/home/observatorioalanis/turnos_core/<resto>` | Controladores, modelos, vistas, rutas, mailers |

*(Al conectar por FileZilla verás la raíz remota del usuario con las carpetas `public_html/` y `turnos_core/` directamente visibles.)*

---

## 2. Archivos Prohibidos (NUNCA sobrescribir en producción)

Antes de subir cualquier actualización, verificar que **nunca** se incluyan:

- `app/.env` $\rightarrow$ en producción `turnos_core/.env` tiene credenciales propias de MariaDB y SMTP.
- `app/public/index.php` $\rightarrow$ en producción `public_html/index.php` apunta a `../turnos_core/`.
- Cachés y logs locales: `app/bootstrap/cache/*`, `app/storage/logs/*`, `app/storage/framework/views/*`, `app/storage/framework/sessions/*`.
- Carpetas fuera de `app/`: `docs/`, `informe_final/`, `docker/`, `.agents/`, `deploy_produccion/`, `graphify-out/` y `app/tests/`.

---

## 3. Cómo Saber Exactamente Qué Archivos Subir

El repositorio incluye un script auxiliar que compara tus cambios desde el último despliegue marcado (`.agents/skills/deploy-filezilla/.last_deploy_commit`) hasta el último commit (`HEAD`), filtrando automáticamente archivos protegidos:

```bash
# Ver qué archivos cambiaron desde el último despliegue marcado:
python .agents/skills/deploy-filezilla/scripts/plan_deploy.py

# Ver solo cambios sin commitear:
python .agents/skills/deploy-filezilla/scripts/plan_deploy.py --uncommitted

# Comparar desde un commit específico (por ejemplo, desde el despliegue inicial 3e2e31c):
python .agents/skills/deploy-filezilla/scripts/plan_deploy.py --since 3e2e31c
```

> **¿Hace falta meter todo en un solo commit?** No. Una vez marcado el último commit subido a producción (`.last_deploy_commit`), el script junta automáticamente todos los commits acumulados hasta `HEAD` y sube solo la diferencia neta.

---

## 4. Opción A (Más Rápida — 100% Automática): Subida Directa por FTPS desde Consola

Sin abrir FileZilla ni Usermin. El script se conecta por FTPS cifrado (`cpanel.exa.unsa.edu.ar:21`), sortea automáticamente el error `550 Overwrite permission denied` de ProFTPD (borra cada archivo viejo antes de subir el nuevo), crea carpetas faltantes, **limpia automáticamente la caché de vistas Blade y rutas** en el servidor y actualiza `.last_deploy_commit` al finalizar:

```powershell
# 1. Simular primero sin tocar el servidor (recomendado):
python .agents/skills/deploy-filezilla/scripts/plan_deploy.py --since 3e2e31c --deploy-ftps --dry-run

# 2. Subir efectivamente a producción (te pide la contraseña FTPS una vez):
python .agents/skills/deploy-filezilla/scripts/plan_deploy.py --since 3e2e31c --deploy-ftps

# (Opcional) Si agregás --save-credentials, guarda la clave solo en deploy_produccion/.ftps_config.json (ignorado por Git).
# En futuros despliegues, como ya queda guardado .last_deploy_commit, alcanza con:
python .agents/skills/deploy-filezilla/scripts/plan_deploy.py --deploy-ftps
```

---

## 5. Opción B (Respaldo sin consola FTPS): Parche ZIP Incremental + Usermin

Si alguna vez preferís subir un solo archivo o actualizás `vendor/`:

### Paso a paso
1. **Generar el ZIP incremental en tu PC:**
   ```bash
   python .agents/skills/deploy-filezilla/scripts/plan_deploy.py --build-zip
   ```
   El script empaqueta únicamente los archivos modificados/nuevos con sus rutas exactas (`turnos_core/...` y `public_html/...`) dentro de `deploy_produccion/` (por ejemplo `patch_deploy_YYYYMMDD_HHMMSS.zip`).
2. **Subir el ZIP por FileZilla:**
   - Conectate a `cpanel.exa.unsa.edu.ar` (puerto `21`, FTPS modo pasivo).
   - En el panel derecho ubicá la raíz del usuario (donde se ven juntas las carpetas `public_html` y `turnos_core`).
   - Arrastrá el archivo `patch_deploy_*.zip` desde tu PC a esa raíz remota.
3. **Extraer en Usermin:**
   - Ingresá a `https://usermin.exa.unsa.edu.ar` $\rightarrow$ **File Manager**.
   - Buscá el `patch_deploy_*.zip` en la raíz (`/home/observatorioalanis/`).
   - Clic derecho $\rightarrow$ **Extract** (Usermin sí sobrescribe archivos existentes).
4. **Limpieza obligatoria:**
   - Una vez extraído, **borrá el `patch_deploy_*.zip`** del servidor y marcá el despliegue con `--mark-deployed`.

---

## 6. Opción C (Para 1 a 5 archivos): Subida Manual por FileZilla

El servidor FTP tiene `AllowOverwrite off` (ProFTPD). Si arrastrás un archivo que ya existe en el servidor, FileZilla devolverá **`550 Overwrite permission denied`**.

- **Archivos modificados (`REEMPLAZAR`):**
  1. En el **panel derecho (Servidor)**, navegar hasta la carpeta destino.
  2. Hacer clic derecho sobre el archivo viejo $\rightarrow$ **Borrar**.
  3. Recién ahí arrastrar el archivo nuevo desde el **panel izquierdo (Tu PC)**.
- **Archivos nuevos (`SUBIR NUEVO`):**
  - Arrastrar directo al panel derecho (creando la subcarpeta primero si no existe).
- **Archivos eliminados (`ELIMINAR`):**
  - Borrarlos directamente en el panel derecho.

---

## 7. Pasos Extra en Usermin Según el Tipo de Cambio

Revisá qué tipo de archivos incluyó tu actualización y aplicá solo los pasos que correspondan:

| Si modificaste... | Qué hacer en producción (Usermin) |
|---|---|
| **Vistas Blade** (`resources/views/*.blade.php`) | Si el cambio visual no aparece al recargar, entrar en **File Manager** $\rightarrow$ `turnos_core/storage/framework/views/` y borrar todos los archivos `.php` compilados (**conservar `.gitignore`**). |
| **Rutas o Config** (`routes/web.php`, `config/*.php`) | En `turnos_core/bootstrap/cache/`, verificar que no existan archivos viejos `config.php` o `routes-*.php`; si existen, borrarlos para que Laravel tome las rutas nuevas al instante. |
| **Clases PHP nuevas** (`app/app/...`, ej. un nuevo `Mail` o `Controller`) | Verificar respeto estricto de mayúsculas/minúsculas (Linux es *case-sensitive*, ej. `frontend/`). Si producción usa *classmap* optimizado, ejecutar en local `docker-compose exec app composer dump-autoload` y subir también `app/vendor/composer/autoload_classmap.php` y `autoload_static.php` a `turnos_core/vendor/composer/`. |
| **Assets JS/CSS** (`resources/js/*`, `resources/sass/*`) | Compilar primero en local (`docker-compose exec app npm run prod`) y asegurarte de subir los archivos compilados en `app/public/js/` o `app/public/css/` a `public_html/`. |
| **Base de datos** (`database/migrations/*` o `seeders/*`) | **1)** Entrar en Usermin $\rightarrow$ **MySQL Database** $\rightarrow$ `observatorioalanis` $\rightarrow$ **Backup Database** primero. **2)** Ejecutar el bloque SQL (`ALTER TABLE` / `CREATE TABLE` / `INSERT`) desde **Execute SQL**. |

---

## 8. Verificación Final y Cierre del Despliegue

1. **Comprobar en el navegador** (recargando con `Ctrl + Shift + R` para evitar caché):
   - Sitio principal: `https://observatorioalanis.exa.unsa.edu.ar` *(o `/preview-unsa2026` si el modo mantenimiento `storage/framework/down` sigue activo)*.
   - Secciones modificadas: `/historia`, `/turnos`, consulta de turno y descarga de comprobante PDF.
2. **Revisar errores si algo falla:** abrir en Usermin `turnos_core/storage/logs/laravel.log` y consultar las últimas líneas.
3. **Marcar el punto de despliegue en tu PC** (una vez confirmado que producción funciona bien):
   ```bash
   python .agents/skills/deploy-filezilla/scripts/plan_deploy.py --mark-deployed
   ```
   Esto registra el commit actual (`HEAD`) para que la próxima vez el script detecte únicamente los cambios nuevos posteriores a este despliegue.
