# Guion y Plan de Defensa de Trabajo Final (Seminario Técnico Profesional)
**Carrera:** Tecnicatura Universitaria en Programación — Facultad de Ciencias Exactas (UNSa)  
**Proyecto:** Sistema de Gestión de Turnos Académicos — Observatorio Alanís y Dependencias UNSa  
**Alumno:** Juan Martin Feijoó  
**Tiempo estimado total:** 45 minutos (30-35 min de exposición + 10 min de preguntas)

---

## ⏱️ Estructura Temporal de la Presentación

| Bloque | Sección | Tiempo | Tipo de Apoyo |
| :---: | :--- | :---: | :--- |
| **1** | Presentación personal, contexto y problemática | 4 min | Diapositivas |
| **2** | Objetivos del proyecto y alcance | 3 min | Diapositivas |
| **3** | Arquitectura del sistema y stack tecnológico | 4 min | Diapositivas / Diagrama |
| **4** | Modelo de datos y decisiones de diseño | 4 min | Diapositivas / DER |
| **5** | **DEMOSTRACIÓN EN VIVO (Live Demo)** | **18 min** | **Navegador en vivo (localhost:8083)** |
| **6** | Seguridad y puesta en producción (hosting UNSa) | 3 min | Diapositivas |
| **7** | Conclusiones, aprendizajes y trabajos futuros | 3 min | Diapositivas |
| **8** | Ronda de preguntas y defensa ante el tribunal | 6 min | Interacción directa |

---

## 🎤 Guion Paso a Paso (Pitch Minuto a Minuto)

### BLOQUE 1: Introducción y Problemática (Minutos 0 a 4)

#### Qué decir:
> "Buenos días a los miembros del tribunal evaluador, profesores y presentes. Mi nombre es **Juan Martin Feijoó** y hoy voy a presentar mi trabajo final de carrera para la Tecnicatura Universitaria en Programación: el **Sistema de Gestión de Turnos Académicos**, con foco principal y caso de aplicación real en el **Observatorio Astronómico Dr. Elvio Alanís** de nuestra Facultad de Ciencias Exactas.
>
> En las instituciones académicas, la asignación de turnos y visitas suele realizarse mediante canales fragmentados: correos electrónicos, planillas de cálculo aisladas, consultas telefónicas o colas presenciales. En el caso del Observatorio Alanís, un espacio que convoca tanto a la comunidad universitaria como a delegaciones de colegios primarios y secundarios de toda la provincia, esta fragmentación causaba tres problemas críticos:
> 1. **Falta de previsibilidad y sobrecupo:** No existía un cálculo en tiempo real que cruzara la capacidad de atención con los guías disponibles.
> 2. **Imposibilidad de respuesta ágil ante contingencias climáticas:** La observación astronómica depende estrictamente del clima. Si una noche llueve o hay cielo cubierto, cancelar 50 turnos llamando por teléfono o enviando correos uno a uno era insostenible.
> 3. **Ausencia de trazabilidad y datos estadísticos:** La facultad carecía de métricas claras sobre la afluencia, demanda por tipo de trámite y productividad de los puestos de atención.
>
> Ante este escenario, surgió el proyecto de desarrollar una plataforma integral, escalable y adaptada a la estructura de la UNSa."

---

### BLOQUE 2: Objetivos y Alcance (Minutos 4 a 7)

#### Qué decir:
> "El **objetivo general** fue diseñar, desarrollar e implementar una plataforma web de gestión integral de turnos académicos que centralice la administración de dependencias y servicios, proveyendo a la comunidad universitaria y a los visitantes un flujo de autogestión ágil y a las autoridades herramientas de control de capacidad, estadísticas y notificaciones.
>
> Como **objetivos específicos**, nos propusimos:
> - Modelar la estructura universitaria multinivel mediante dependencias jerárquicas.
> - Construir un asistente de reserva reactivo en tres pasos que valide fechas inhábiles (feriados/asuetos) y cupos en tiempo real.
> - Soportar modalidades individuales y de delegaciones escolares, permitiendo la carga diferida de nóminas de alumnos y docentes para control de seguro y aforo.
> - Implementar un sistema de control de capacidad dinámica mediante **mesas habilitadas con interruptor rápido (*toggle*)**.
> - Proveer una herramienta de **cancelación masiva por contingencias climáticas** con despacho automatizado de correos.
> - Integrar un **gestor de plantillas de correo transaccionales** con editor web y variables dinámicas, diferenciando el circuito individual del escolar.
> - Proteger el sistema mediante control de acceso basado en roles (RBAC) y Single Sign-On (SSO) con Google."

---

### BLOQUE 3: Stack Tecnológico y Arquitectura (Minutos 7 a 11)

#### Qué decir:
> "Para el desarrollo del sistema se adoptó una arquitectura **MVC (Modelo-Vista-Controlador)** sobre tecnologías consolidadas de la industria:
>
> - **Contenerización con Docker y Docker Compose:** Para aislar el entorno de desarrollo y asegurar paridad con el servidor de producción, orquestando tres contenedores: PHP-FPM, Nginx y MySQL.
> - **Backend en PHP con Laravel 8.x:** Aprovechando Eloquent ORM, migraciones versionadas, *FormRequests* para validaciones rigurosas y *Mailables*.
> - **Frontend Híbrido y Reactivo:** Vistas Blade estructuradas bajo componentes de **AdminLTE 4** en el panel administrativo, y componentes reactivos en **Vue.js 2** para la selección dinámica de turnos y consulta asíncrona de disponibilidad vía AJAX sin recargar la página.
> - **Paquetes clave:**
>   - `spatie/laravel-permission`: Para el control granular de roles y permisos.
>   - `laravel/socialite`: Para el inicio de sesión unificado con cuentas institucionales de Google.
>   - `barryvdh/laravel-snappy` con `wkhtmltopdf`: Para el renderizado de alta fidelidad de comprobantes y reportes oficiales en PDF."

---

### BLOQUE 4: Modelo de Datos y Decisiones de Diseño (Minutos 11 a 15)

#### Qué decir:
> "El modelo relacional fue diseñado priorizando la integridad referencial y el rendimiento de las consultas concurrentes:
>
> 1. **Estructura Jerárquica con Nested Set:** En la tabla `dependencias` no usamos simplemente un `parent_id`, sino el patrón *Nested Set* con columnas `_lft` y `_rgt`. Esto permite que una consulta sobre una facultad recupere en una única operación indexada todas sus sub-dependencias, cátedras o laboratorios sin recursión costosa.
> 2. **Cálculo de Aforo Dinámico:** El cruce entre `turnos_horarios`, `mesas_habilitadas` y `dependencia_turnos_reservas` permite que la disponibilidad no sea un valor fijo, sino el resultado de contrastar la capacidad teórica de los puestos activos contra la sumatoria de personas reservadas en la franja.
> 3. **Contingentes Escolares y Carga Diferida:** La relación `dependencia_turnos_reservas` con `reserva_integrantes` permite que un docente reserve el turno institucional y posteriormente complete o modifique la nómina de alumnos mediante un enlace público protegido por código de reserva.
> 4. **Motor de Plantillas de Email:** La tabla `plantillas_email` desacopla el contenido de las notificaciones del código duro, soportando sustitución léxica de variables como `{codigo}`, `{nombre}`, `{institucion}` y `{fecha}`."

---

### BLOQUE 5: DEMOSTRACIÓN EN VIVO / LIVE DEMO (Minutos 15 a 35)

*(Abre el navegador en pantalla completa en `http://localhost:8083`)*

#### Paso 1: Portal Institucional y Consulta Pública (3 min)
1. **Mostrar la portada:**
   - *Decir:* "Aquí vemos el portal institucional del Observatorio. En la parte superior encontramos accesos directos y tarjetas de actividades principales."
   - *Acción:* Mostrar la sección de Historia o Instalaciones brevemente para que vean la multi-página.
2. **Buscador público sin login:**
   - *Acción:* Clic en **Consultar Turno** (`/turno.buscar`).
   - *Decir:* "Cualquier persona que ya tenga un turno puede consultar su estado con DNI o código sin necesidad de registrarse."

#### Paso 2: Flujo de Reserva Reactivo en 3 Pasos (6 min)
1. **Paso 1 - Selección:**
   - *Acción:* Clic en **Reservar Turno** (`/turnos`).
   - *Acción:* Seleccionar Dependencia: **Observatorio Alanís** -> Trámite: **Visita Guiada y Taller de Astronomía**.
   - *Decir:* "Al seleccionar la dependencia, una petición AJAX carga al instante los trámites vigentes."
2. **Paso 2 - Calendario Reactivo (Vue.js):**
   - *Acción:* Avanzar al Paso 2.
   - *Decir:* "Observen el calendario. El componente Vue consulta automáticamente la API interna: los fines de semana y los feriados o asuetos de la UNSa están deshabilitados. Al hacer clic en un día hábil, el sistema calcula los horarios libres según los cupos de las mesas activas."
   - *Acción:* Seleccionar un horario disponible y avanzar al Paso 3.
3. **Paso 3 - Formulario y Modalidad Escolar:**
   - *Acción:* Completar DNI, Nombre, Email.
   - *Acción:* Seleccionar **Modalidad Delegación Escolar**.
   - *Decir:* "Noten cómo el formulario despliega los campos específicos: Institución educativa, Nivel, Curso, Docente responsable y Cantidad de alumnos."
   - *Acción:* Confirmar la reserva.
4. **Comprobante PDF y Código:**
   - *Decir:* "El sistema genera un código alfanumérico único, emite el PDF oficial mediante Snappy y dispara el correo de confirmación."
   - *Acción:* Abrir y mostrar el PDF generado.

#### Paso 3: Carga de Integrantes Diferida y Panel "Mis Turnos" (4 min)
1. **Carga pública de integrantes:**
   - *Acción:* Ingresar a la URL `/reserva/{codigo}/integrantes`.
   - *Decir:* "El directivo escolar puede ingresar a este enlace con su código para cargar la nómina de alumnos y acompañantes con sus DNI para el seguro escolar."
2. **Panel "Mis Turnos":**
   - *Acción:* Iniciar sesión como usuario regular (`/login`).
   - *Decir:* "El sistema detecta que es un rol USUARIO y lo redirige automáticamente a `mis-turnos`. Aquí tiene su historial, puede reimprimir el comprobante o cancelar su cita indicando un motivo. Al cancelar, el cupo se libera al instante en el calendario."

#### Paso 4: Panel Administrativo, Cancelación Masiva y Mesas Habilitadas (7 min)
1. **Inicio de sesión Administrador:**
   - *Acción:* Salir y loguearse con `admin@admin.com` / `123456`.
2. **Dashboard Operativo:**
   - *Decir:* "Aquí vemos las métricas en tiempo real de la jornada: turnos pendientes, atendidos, ausencias y acumulado mensual."
3. **Bandeja de Gestión de Reservas (`/turnosdependenciasreservas`):**
   - *Acción:* Mostrar la grilla con multifiltros (por fecha, trámite, DNI).
   - *Acción:* Mostrar el botón de **Asignación Manual** para turnos extraordinarios o presenciales.
4. **Demostración de Cancelación Masiva (Punto Fuerte):**
   - *Acción:* Mostrar el modal o formulario de **Cancelación Masiva**.
   - *Decir:* "Supongamos que para la noche de hoy el pronóstico anticipa tormenta y cielo cubierto. El administrador selecciona la fecha de hoy, ingresa el motivo: *'Cancelación por contingencia climática e imposibilidad de visibilidad astronómica'*, marca *Notificar por correo* y ejecuta la acción. En una sola transacción se anulan todas las citas del turno y cada visitante recibe el correo con la explicación oficial."
5. **Mesas Habilitadas y el Interruptor (*Toggle*):**
   - *Acción:* Ir a `/mesashabilitadas`.
   - *Decir:* "Cada mesa representa un puesto o un guía. Si un guía avisa que no puede asistir, hacemos clic en el interruptor (*toggle*): la mesa se deshabilita inmediatamente y el portal público reduce el aforo disponible sin reiniciar la configuración."
6. **Gestor de Plantillas de Correo:**
   - *Acción:* Ir a `/plantillas-email`.
   - *Decir:* "El administrador puede seleccionar el circuito Individual o Colegios, editar el cuerpo HTML usando variables dinámicas como `{codigo}` o `{institucion}`, previsualizar en vivo con datos de prueba o enviarse un mail de test a su casilla real."
7. **Estadísticas y Reportes:**
   - *Acción:* Mostrar brevemente `/estadisticas` y `/reporte.operador` (exportación a Excel y partes de firmas).

---

### BLOQUE 6: Seguridad y Despliegue en Servidor UNSa (Minutos 35 a 38)

#### Qué decir:
> "El sistema implementa sólidas capas de seguridad:
> - **RBAC con Spatie:** Diferenciación de perfiles (Super Administrador, Administrador de Dependencia, Operador y Usuario Regular) protegido por middlewares en rutas y directivas `@can` en Blade.
> - **Protección contra vulnerabilidades comunes:** Sanitización con *FormRequests*, protección nativa contra inyecciones SQL mediante PDO parametrizado en Eloquent y tokens CSRF en todas las solicitudes mutables.
> - **Despliegue en la UNSa:** El proyecto fue preparado para el servidor institucional de la Facultad de Ciencias Exactas (`observatorioalanis.exa.unsa.edu.ar`), aislando el núcleo de Laravel fuera de la carpeta pública `public_html/` y conectando a una base de datos MariaDB y servidor SMTP institucional."

---

### BLOQUE 7: Conclusiones y Aprendizajes (Minutos 38 a 41)

#### Qué decir:
> "Para concluir, el desarrollo de este trabajo final representó una experiencia profesional completa:
> 1. **Transformación de procesos reales:** Se reemplazó un flujo manual y desarticulado por un sistema web automatizado y transparente para la universidad.
> 2. **Desafíos técnicos superados:** El cálculo reactivo de disponibilidad en tiempo real, la gestión de contingencias climáticas masivas y la arquitectura de plantillas transaccionales por circuitos.
> 3. **Líneas de trabajo futuro:** Como evolución natural, se proyecta la integración con la API de SIU-Guaraní para la precarga de estudiantes regulares de la UNSa y una Progressive Web App (PWA) con notificaciones push para recordatorios de visitas.
>
> Agradezco a los profesores de la tecnicatura por su formación y al personal del Observatorio por su colaboración. Quedo a disposición del tribunal para responder a sus preguntas."

---

## 🛡️ Banco de Preguntas Típicas del Tribunal (con Respuestas Preparadas)

### Pregunta 1: *"¿Por qué decidiste modelar las dependencias con Nested Set en lugar de una relación jerárquica simple (parent_id)?"*
- **Respuesta:** *"Una relación simple con solo `parent_id` (Adjacency List) requiere consultas recursivas iterativas o múltiples JOINs para obtener todos los hijos y sub-dependencias, lo que degrada el rendimiento a medida que el árbol universitario crece. El patrón Nested Set (con `_lft` y `_rgt`) permite obtener un subárbol completo (por ejemplo, el Rectorado, una Facultad y todos sus departamentos) mediante una única consulta SQL indexada `WHERE _lft BETWEEN x AND y`, optimizando drásticamente los tiempos de respuesta en consultas públicas."*

### Pregunta 2: *"¿Cómo se evita que dos usuarios reserven el mismo cupo al mismo tiempo en el calendario?"*
- **Respuesta:** *"La validación se realiza en dos etapas. En el frontend, Vue.js refresca la disponibilidad en tiempo real vía AJAX. Pero la validación autoritativa ocurre en el backend dentro del método `guardar` con una transacción de base de datos (`DB::transaction`): se verifica nuevamente la sumatoria de turnos ocupados contra las mesas habilitadas bloqueando temporalmente el registro. Si en ese milisegundo el cupo se agotó, la transacción hace rollback y se le notifica amablemente al usuario que el horario acaba de ser ocupado, impidiendo cualquier condición de carrera."*

### Pregunta 3: *"¿Por qué utilizaste Snappy (wkhtmltopdf) en lugar de librerías como DomPDF o FPDF?"*
- **Respuesta:** *"DomPDF tiene soporte limitado para CSS moderno, flexbox y renderizado de tipografías vectoriales complejas. `laravel-snappy`, al estar basado en el motor headless de WebKit (`wkhtmltopdf`), compila las vistas Blade exactamente igual a como las dibuja un navegador web moderno, garantizando una fidelidad gráfica perfecta en los comprobantes y reportes con el membrete oficial de la UNSa."*

### Pregunta 4: *"¿Cómo se manejan los permisos y la segregación entre distintas dependencias?"*
- **Respuesta:** *"Combinamos dos niveles de control: a nivel de acciones utilizamos `spatie/laravel-permission` con roles (Administrador, Operador, Usuario). A nivel de datos, implementamos la tabla pivote `usuarios_dependencias`. Cuando un operador ingresa a la bandeja de turnos, el controlador filtra las consultas con un `whereIn('dependencia_id', $user_dependencias)`, impidiendo que un operador de una facultad visualice o altere reservas de otra dependencia."*

### Pregunta 5: *"¿Cómo impacta el interruptor rápido (Toggle) de mesas habilitadas en el cálculo de disponibilidad?"*
- **Respuesta:** *"La tabla `mesas_habilitadas` cuenta con la columna booleana `habilitado`. El método `loadHorarios` solo computa la capacidad total multiplicando las franjas horarias por las mesas cuyo estado es `habilitado = 1`. Al presionar el toggle, la actualización es inmediata en la base de datos, por lo que la próxima consulta AJAX desde el portal público recalcula y reduce automáticamente el aforo disponible."*

---

## 🎒 Checklist Previo a la Defensa

- [ ] Docker corriendo (`docker ps` confirma `turnos_nginx`, `turnos_app`, `turnos_db`).
- [ ] Base de datos con datos de prueba cargados (feriados cargados, turnos en diferentes estados).
- [ ] Navegador listo en pantalla completa con pestañas pre-abiertas:
  - Pestaña 1: `http://localhost:8083` (Portal público)
  - Pestaña 2: `http://localhost:8083/login` (Portal de acceso)
  - Pestaña 3: `http://localhost:8083/home` (Panel administrativo - con sesión iniciada)
- [ ] PDF del informe final generado y disponible en pendrive o escritorio: [`informe_final/build/informe_final.pdf`](file:///c:/Users/feijo/Desktop/Turnos/informe_final/build/informe_final.pdf).
- [ ] Teléfono en silencio y cronómetro a la vista para controlar los 45 minutos.
