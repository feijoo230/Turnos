# Mejoras Implementadas y Respuestas Técnicas para la Defensa (Anexo 2)

**Proyecto:** Sistema de Gestión de Turnos y Portal Institucional — Observatorio Astronómico Prof. Elvio Alanís (Facultad de Ciencias Exactas, UNSa)  
**Fecha de actualización:** Noviembre 2026  

Este documento resume las mejoras visuales, de experiencia de usuario (UX), de arquitectura de concurrencia, de seguridad y del circuito de confirmación implementadas en el sistema, junto con las respuestas técnicas concretas a las consultas planteadas para la presentación previa a la defensa.

---

## 1. Actualización Fotográfica e Identidad Visual

Se reemplazaron las imágenes genéricas y las capturas de diapositivas de PowerPoint por las fotografías originales del archivo histórico del Observatorio (recortadas, optimizadas para web y con orientación EXIF corregida):

- **Fondo principal del Home y del Portal de Turnos** (`app/public/img/observatorio/hero-bg.jpg`): fotografía histórica del edificio de la UNSa con la cúpula del Observatorio, aplicada en `app/resources/views/frontend/landing.blade.php` y `app/resources/views/layouts/frontend.blade.php` (reemplazando fondos genéricos).
- **Galería cronológica de Historia** (`app/resources/views/frontend/observatorio/historia.blade.php`):
  - **1985 — Los inicios:** `historia-inicios-asa.jpg` (extraída en alta resolución del PDF *Equipo ASA 1990*).
  - **1986–1988 — La cúpula:** secuencia completa en 3 fotografías (`historia-cupula-1.jpg`, `historia-cupula-2.jpg` e `historia-cupula-3.jpg` en la inauguración junto al Prof. Elvio Alanís).
  - **1988–1994 — El telescopio:** `historia-telescopio.jpg` (reflector newtoniano de 500 mm, f/6).
  - **2023 — Reconocimiento Institucional:** retrato del Prof. Elvio Alanís (`historia-nombre.jpg`) y votación del Departamento de Física aprobando la imposición del nombre (`historia-aprobacion-nombre.jpg`).
  - Se incorporó un visor *lightbox* liviano para ampliar cualquier fotografía con su epígrafe.

---

## 2. Refactorización de UI y Mejoras UX (Issues #24 y #20)

- **Rediseño del Paso 1 (`form-turno-paso1.blade.php`):**
  - Se reemplazaron los dos `<select size="7">` clásicos por tarjetas interactivas divididas en **A. Espacio u Oficina** y **B. Tipo de Visita o Actividad**, sin depender de recompilar Vue/Webpack.
  - Autoselección automática cuando existe un único espacio o actividad con turnos disponibles.
  - Ordenamiento prioritario (actividades con cupos disponibles primero) e identificación visual de modalidad (*Individual*, *Visita Grupal*, *Escuelas / Instituciones*, *Mixto*) y rango permitido de personas.
- **Paso 2 — Calendario y Horarios (`form-turno-paso2.blade.php` y `TramitesController::loadHorarios`):**
  - Resumen superior de la actividad elegida con botón para cambiarla.
  - Calendario con diferenciación visual clara y leyenda (*celeste*: días con turnos, *verde*: día elegido, *gris*: sin turnos o feriado).
  - Reemplazo del `<select size="30">` por tarjetas/botones tocables con hora limpia (`19:00 hs`) y disponibilidad real, autoselección si hay un único horario y validación amigable antes de avanzar.
- **Paso 3 — Datos del visitante (`form-turno-paso3.blade.php`):**
  - Corrección del formato de hora mostrado en el resumen (antes imprimía `19:00:00|5 hs`; ahora `19:00 hs`).
  - Validación en vivo de coincidencia de correo electrónico, textos de ayuda sobrios (DNI sin puntos, celular sin 0 ni 15), tooltips puntuales y protección contra doble envío.
- **Paso 4 y Comprobante PDF (`form-turno-paso4.blade.php` y `htmltopdf/turno_comprobante.blade.php`):**
  - Corrección de bug de hora en `TramitesController::print()` (`h:i` de 12 horas $\rightarrow$ `H:i` de 24 horas).
  - Botón de **Copiar Código** en un clic e indicación explícita del estado de la reserva y envío por correo.
  - Comprobante PDF institucional apto para pantalla e impresión B&N (100% compatible con `wkhtmltopdf 0.12.4`): encabezado limpio con logo UNSa en base64, código de reserva, **sello HMAC de verificación** (`XXXX-XXXX`), fecha/hora 24h, estado actual del circuito, tabla completa de datos, **nómina de integrantes impresa automáticamente** cuando la escuela/grupo ya la cargó, y recomendaciones prácticas.

---

## 3. Consulta 1 del Profesor: Disponibilidad y Cupos con Pedidos Simultáneos

### Problema detectado antes de la mejora
1. **Condición de carrera (*overbooking*):** el cálculo de cupos disponibles y el `INSERT` en `dependencia_turnos_reservas` ocurrían fuera de una transacción y sin bloqueo de fila. Dos peticiones simultáneas podían leer el mismo cupo libre y provocar sobreventa.
2. **Inconsistencia entre modalidades en trámites `mixto`:** si una escuela reservaba dentro de un trámite mixto, el sistema contaba reservas (`COUNT(*)`) en lugar de plazas físicas (`SUM(cantidad_personas)`), permitiendo exceder la capacidad real.
3. **Desfasajes entre pantallas:** `Dependencia::getDependenciasConTurnos()` no excluía reservas canceladas (`estado_id != 4`) ni diferenciaba personas; `getDisabledDates()` no agrupaba por `turno_horario_id` ni descartaba horarios ya pasados del día actual; y el módulo de reserva manual del administrador referenciaba columnas obsoletas (`dia_id`, `hora`).

### Solución implementada
1. **Transacción atómica + bloqueo pesimista a nivel de fila (`SELECT ... FOR UPDATE`):**
   - En `TramitesController::guardar()`, `TurnosDependenciasReservasController::storeManual()` y la reactivación de cancelados en `cambiarEstado()`, toda verificación de cupo + inserción se ejecuta dentro de `DB::transaction(...)`.
   - Al iniciar la transacción se bloquea la fila padre correspondiente en `turnos_horarios`:
     ```php
     Turnos_Horarios::where('id', $turno_horario_id)->lockForUpdate()->first();
     ```
   - **Por qué se bloquea la fila padre `turnos_horarios`:** actúa como un *mutex* por franja horaria en InnoDB incluso cuando todavía hay **0 reservas** cargadas. Si dos pedidos llegan al mismo milisegundo, el segundo espera atómicamente hasta el `COMMIT` del primero y luego evalúa el cupo real actualizado.
2. **Regla unificada por modalidad (`Dependencia_Tramite::usaCupoPorDelegacion()`):**
   - **Exclusivamente `institucional` (Escuelas):** `cantidad_turnos` representa **cupo de delegaciones** por franja horaria (por ejemplo, `1` escuela por turno con rango `min_personas_reserva`–`max_personas_reserva` alumnos). El control se realiza por `COUNT(*)` de reservas con `estado_id != 4`.
   - **`individual`, `grupal` y `mixto`:** `cantidad_turnos` representa la **capacidad física total en personas** (por ejemplo, `30` plazas). El control se realiza siempre por `SUM(cantidad_personas)` de reservas con `estado_id != 4`, incluso cuando una escuela se inscribe dentro de un trámite `mixto`.
   - Esta misma regla se aplicó de forma idéntica en las 5 capas: `Dependencia::getDependenciasConTurnos()`, `checkAvailability()`, `getDisabledDates()`, `loadHorarios()` y `guardar()` (más `loadHorariosAdmin()` y `storeManual()`).
3. **Controles adicionales estándar de un turnero:**
   - Prevención de **reservas duplicadas** con el mismo DNI en idéntico día y horario dentro de la misma transacción.
   - Revalidación server-side de vigencia del período (`fecha_desde`–`fecha_hasta`), día habilitado, horario no vencido y feriados activos.

---

## 4. Consulta 2 del Profesor: Seguridad de las Reservas

### Vulnerabilidades detectadas antes de la mejora
- **Solo con el DNI de otra persona:** `turno.buscar` devolvía todos los datos personales (y si tenía 2+ turnos descargaba automáticamente un PDF con todos ellos) y habilitaba descargar comprobantes o cancelar.
- **Solo con el código (además secuencial `OBS000001`, `OBS000002`...):** exponía el DNI del titular, permitía entrar a `/reserva/{codigo}/integrantes` sin autenticación (accediendo y modificando la nómina de estudiantes) y permitía cancelar por `POST` omitiendo el campo opcional `dni_cancelacion`.
- **IDOR por ID numérico:** `/turnos.print/{id}` y `/tramite/confirmacion/{id}` eran accesibles públicamente probando IDs correlativos (`1`, `2`, `3`...).

### Modelo de seguridad implementado

| Escenario de ataque o uso | Comportamiento actual del sistema |
|---|---|
| Alguien conoce **solo el DNI** de otra persona | **Bloqueado.** La consulta exige Código y DNI juntos (`SearchTurno`); no puede ver datos, descargar PDF, editar nómina ni cancelar. |
| Alguien conoce **solo el Código** de una reserva | **Bloqueado.** Sin el DNI coincidente, la consulta devuelve un mensaje genérico sin revelar si el código existe; no accede al PDF, nómina ni cancelación. |
| Alguien prueba URLs directas (`/turnos.print/5`, `/tramite/confirmacion/5`, `/reserva/OBS-XXXXX/integrantes`, `POST /cancelar`) | **Bloqueado (HTTP 403 o redirección segura).** Requiere autorización verificada. |
| Fuerza bruta sobre consulta, nómina o cancelación | **Mitigado** mediante códigos no secuenciales + `throttle:15,1` en `routes/web.php` + mensajes de error genéricos (sin filtro de existencia). |
| **Titular legítimo sin login** (acaba de reservar o consulta con **Código + DNI**) | **Autorizado en sesión** (`reservas_autorizadas`). Puede ver confirmación, descargar comprobante PDF, gestionar integrantes y cancelar (confirmando DNI) mientras el turno no haya pasado. |
| **Titular autenticado** en *Mi Portal → Mis Turnos* | **Autorizado por propiedad** (`Auth::user()->email === $reserva->email`). Ve sus reservas, descarga PDF y cancela sin necesidad de recordar el código. |
| **Administrador / Operador** autenticado | **Autorizado por rol** (`ADMINISTRADOR` / `OPERADOR`) para gestión y descarga desde el panel administrativo. |

### Diseño equilibrado de códigos de reserva
Para evitar códigos secuenciales sin volverlos incómodos para el público general, `Turnos_Dependencias_Reservas::generarCodigoUnico()` genera códigos con formato **`OBS-7K4M9`**:
- 5 caracteres generados con `random_int()` sobre un alfabeto sin ambigüedades visuales (`23456789ABCDEFGHJKLMNPQRSTUVWXYZ`, excluyendo `0/O/1/I/L`) $\rightarrow$ $32^5 = 33{.}554{.}432$ combinaciones.
- Búsqueda tolerante a errores de tipeo: insensible a mayúsculas/minúsculas, guiones (`obs7k4m9` = `OBS-7K4M9`) y puntos/espacios en el DNI.
- Sello de integridad HMAC-SHA256 (`codigoVerificacion()`) impreso en el comprobante PDF.

---

## 5. Circuito Completo de Estados y Correos Electrónicos

- **Estados de reserva (`turnos_reservas_estados`):**
  1. `1 — Pendiente`: estado inicial al reservar desde el portal público (**el cupo ya queda apartado**). Dispara automáticamente el nuevo Mailable `App\Mail\TurnoSolicitado` conectado a las plantillas `turno_solicitado_individual` y `turno_solicitado_colegio` del módulo de Plantillas de Email (con vista fallback `emails/turno_solicitado.blade.php`), ejecutado fuera de la transacción de BD dentro de `try/catch`.
  2. `3 — Confirmado`: aprobación desde el panel administrativo (`cambiarEstado` o `storeManual`). Dispara `App\Mail\TurnoConfirmado`.
  3. `4 — Cancelado`: cancelación por el visitante (solo turnos futuros), individual por admin o masiva (`cancelacionMasiva`). Marca `activo = 0`, guarda `motivo_cancelacion`, **libera inmediatamente el cupo** y dispara `App\Mail\TurnoCancelado`.
  4. `2 — Finalizado`: registro de atención completada.
- **Corrección de destinatario en plantillas:** se corrigió la detección `$esColegio` en `TurnoSolicitado`, `TurnoConfirmado` y `TurnoCancelado` para que una reserva grupal familiar (`es_grupal = 1` sin institución) no reciba plantillas institucionales de colegios.
- **Reactivación segura:** si un operador reactiva una reserva previamente cancelada (`4` $\rightarrow$ `1` o `3`), el sistema revalida disponibilidad bajo `lockForUpdate()`.

---

## 6. Verificación Automatizada y Demo en Vivo para la Defensa

### A. Tests automatizados en Laravel (sin alterar datos locales)
El archivo `app/tests/Feature/TurnosSeguridadYConcurrenciaTest.php` utiliza `DatabaseTransactions` y `Mail::fake()` (hace rollback automático al finalizar):

```bash
docker-compose exec app php artisan test --filter=TurnosSeguridadYConcurrenciaTest
```

Valida:
1. Cupo unificado en trámite **mixto** (Individual + Grupo + Escuela sin sobreventa) y rechazo de DNI duplicado.
2. Cupo por delegación en trámite **institucional (Escuelas)** y formato de código no secuencial.
3. Bloqueo de consulta por **DNI solo**, **Código solo**, **IDOR** en PDF/confirmación/nómina/cancelación y flujo legítimo **Código + DNI** con liberación de cupo al cancelar.

### B. Script de demo en vivo (peticiones HTTP paralelas y seguridad)
El script `docs/demo_concurrencia_defensa.py` (solo librería estándar de Python) descubre automáticamente un espacio, actividad, fecha y horario activos en tu servidor local, dispara 5 pedidos simultáneos sincronizados con `threading.Barrier` y luego ejecuta las 8 pruebas de seguridad contra la reserva real generada:

```bash
# Modo automático completo (concurrencia + seguridad en una sola ejecución):
python docs/demo_concurrencia_defensa.py --url http://localhost:8083 --hilos 5 --personas 10

# O probar solo seguridad sobre una reserva específica existente:
python docs/demo_concurrencia_defensa.py --url http://localhost:8083 --solo-seguridad --reserva-id 15 --codigo OBS-7K4M9 --dni 30123456
```

### Nota sobre permisos en desarrollo local (Docker en Windows)
Si al modificar vistas Blade en local aparece `Permission denied` en `storage/framework/views/*.php`, se resuelve vaciando la caché compilada:

```bash
docker-compose exec app php artisan view:clear
# Si persiste con permisos del volumen:
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```
