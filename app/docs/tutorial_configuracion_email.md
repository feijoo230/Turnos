# Guía de Configuración del Servicio de Correo Electrónico (SMTP / Gmail)

Esta guía explica cómo configurar el envío de correos electrónicos en el **Sistema de Gestión de Turnos (UNSa)** para notificaciones automáticas de reserva, confirmación y cancelación masiva.

---

## 🛠️ 1. Configuración de Variables en el Archivo `.env`

Abre el archivo `.env` ubicado en la raíz del proyecto (`app/.env`) y edita o agrega los siguientes parámetros de correo según tu servidor SMTP:

### Ejemplo para Servidor SMTP Estándar de la UNSa:
```env
MAIL_MAILER=smtp
MAIL_HOST=mail.unsa.edu.ar
MAIL_PORT=587
MAIL_USERNAME=turnos@unsa.edu.ar
MAIL_PASSWORD=TuContraseñaSegura123
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="turnos@unsa.edu.ar"
MAIL_FROM_NAME="Sistema de Turnos UNSa"
```

### Ejemplo para Cuenta Gmail (con Contraseña de Aplicación):
> **Nota:** Si utilizas Gmail, debes habilitar la verificación en dos pasos en tu cuenta de Google y generar una **Contraseña de Aplicación** específica de 16 caracteres.

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_cuenta@gmail.com
MAIL_PASSWORD=abcd1234efgh5678
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="tu_cuenta@gmail.com"
MAIL_FROM_NAME="Sistema de Turnos UNSa"
```

---

## 🧪 2. Modo de Pruebas Local (Sin Servidor SMTP)

Si estás desarrollando en un entorno local y no deseas enviar correos reales, puedes cambiar el driver a `log`. En este modo, todos los correos generados se registrarán en texto plano dentro del archivo `storage/logs/laravel.log`:

```env
MAIL_MAILER=log
```

---

## ⚡ 3. Limpieza de Caché de Configuración

Cada vez que modifiques el archivo `.env`, debes limpiar la caché de Laravel ejecutando los siguientes comandos en la terminal de la aplicación:

```bash
php artisan config:clear
php artisan cache:clear
```

---

## 📩 4. Funcionalidades del Sistema que Utlizan Correo
- **Confirmación de Reserva Manual (`App\Mail\TurnoConfirmado`):** Notificación automática con código `TUR-XXXXXX` al registrar o aprobar un turno.
- **Cancelación Individual o Masiva (`App\Mail\TurnoCancelado`):** Notificación enviada cuando un turno es cancelado por el ciudadano o masivamente por el administrador por emergencias climáticas.
