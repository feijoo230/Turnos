# Guía y Tutorial: Plantillas y Envío de Correos Electrónicos en Laravel

Esta guía técnica explica en detalle cómo funciona la arquitectura de envío de correos electrónicos y plantillas en **Laravel** en comparación con plataformas como **Odoo**, y cómo crear, personalizar y probar plantillas HTML en este proyecto.

---

## 🎯 1. Conceptos Fundamentales: Laravel vs. Odoo

En plataformas ERP como **Odoo**, las plantillas de correo (`mail.template`) se gestionan en la base de datos con un editor WYSIWYG en la interfaz web y sintaxis Jinja2/QWeb.

En **Laravel**, el sistema de correos se basa en código fuente y utiliza dos componentes desacoplados:
1. **La Clase Mailable (`app/Mail/...`):** Contiene la lógica PHP, los destinatarios, el asunto, los archivos adjuntos y la preparación de los datos.
2. **La Vista Blade (`resources/views/emails/...`):** Es la plantilla visual HTML/CSS que renderiza el cuerpo del mensaje utilizando sintaxis Blade (`{{ $variable }}`, `@if`, `@foreach`).

---

## 🛠️ 2. Estructura de un Envío de Correo en Laravel

### Paso A: Creación del Mailable (`app/Mail/TurnoConfirmado.php`)

Un Mailable es una clase que extiende de `Illuminate\Mail\Mailable`. Se encarga de construir la estructura del correo.

```php
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Turnos_Dependencias_Reservas;

class TurnoConfirmado extends Mailable
{
    use Queueable, SerializesModels;

    public $reserva; // Propiedad pública disponible automáticamente en la vista Blade

    /**
     * Inyección del modelo o datos al crear la instancia
     */
    public function __construct(Turnos_Dependencias_Reservas $reserva)
    {
        $this->reserva = $reserva;
    }

    /**
     * Construcción del mensaje
     */
    public function build()
    {
        $fromEmail = config('mail.from.address', 'turnos@unsa.edu.ar');
        $fromName = config('mail.from.name', 'Sistema de Turnos UNSa');

        return $this->from($fromEmail, $fromName)
            ->subject('Confirmación de Turno Reservado - ' . $this->reserva->codigo)
            ->view('emails.turno_confirmado', ['reserva' => $this->reserva]);
    }
}
```

---

### Paso B: Creación de la Vista Blade (`resources/views/emails/turno_confirmado.blade.php`)

La vista contiene el marcado HTML con estilos **CSS inline / embutidos** (recomendado para garantizar compatibilidad con clientes de correo como Outlook, Gmail, Apple Mail):

```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Turno</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; color: #333333; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e1e8ed; }
        .header { text-align: center; border-bottom: 2px solid #16a34a; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { color: #16a34a; margin: 0; }
        .badge-code { background-color: #1e3c72; color: #ffffff; padding: 6px 12px; border-radius: 4px; font-size: 1.1rem; font-weight: bold; }
        .details { background-color: #f8fafc; padding: 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #16a34a; }
        .details p { margin: 6px 0; }
        .footer { text-align: center; font-size: 0.85rem; color: #6b7280; margin-top: 25px; border-top: 1px solid #e5e7eb; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>¡Reserva de Turno Confirmada!</h2>
        </div>
        <p>Estimado/a <strong>{{ $reserva->nombre_apellido }}</strong>,</p>
        <p>Le informamos que su reserva de turno se encuentra <strong>confirmada</strong> en nuestro sistema.</p>
        
        <div class="details">
            <p><strong>Código de Reserva:</strong> <span class="badge-code">{{ $reserva->codigo }}</span></p>
            <p><strong>Fecha y Hora:</strong> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} - {{ $reserva->hora }} hs</p>
            <p><strong>Trámite / Servicio:</strong> {{ $reserva->turno_horario->turno_tramite->tramite->nombre ?? 'Trámite Solicitado' }}</p>
            <p><strong>Dependencia:</strong> {{ $reserva->turno_horario->turno_tramite->tramite->dependencia->nombre ?? 'UNSa' }}</p>
            @if($reserva->nombre_institucion)
                <p><strong>Institución / Delegación:</strong> {{ $reserva->nombre_institucion }}</p>
            @endif
        </div>

        <p>Por favor, conserve este código para verificar su ingreso.</p>

        <div class="footer">
            <p>Sistema de Gestión de Turnos - Universidad Nacional de Salta</p>
            <p>Este es un correo automático, por favor no responda directamente a este mensaje.</p>
        </div>
    </div>
</body>
</html>
```

---

### Paso C: Disparo del Envío en Controladores

Para enviar el correo desde cualquier controlador o servicio:

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\TurnoConfirmado;

if (!empty($reserva->email)) {
    try {
        Mail::to($reserva->email)->send(new TurnoConfirmado($reserva));
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error("Error enviando correo: " . $e->getMessage());
    }
}
```

---

## 📎 3. Adjuntar Archivos (Ej: Comprobante PDF)

Si se desea adjuntar un PDF generado dinámicamente:

```php
public function build()
{
    $pdf = \PDF::loadHTML(view('htmltopdf.turno_comprobante', ['turno_reserva' => $this->reserva])->render());

    return $this->subject('Confirmación de Turno')
        ->view('emails.turno_confirmado')
        ->attachData($pdf->output(), "comprobante_{$this->reserva->codigo}.pdf", [
            'mime' => 'application/pdf',
        ]);
}
```

---

## 🧪 4. Pruebas y Entornos de Desarrollo

En archivo `.env`:
- **Modo Log local (sin SMTP real):**
  ```env
  MAIL_MAILER=log
  ```
  Los correos se escribirán completos en `storage/logs/laravel.log` para ser inspeccionados fácilmente.

- **Modo Servidor SMTP (Producción / Gmail / Mailtrap):**
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.gmail.com
  MAIL_PORT=587
  MAIL_USERNAME=tu_correo@unsa.edu.ar
  MAIL_PASSWORD=tu_contraseña_de_aplicación
  MAIL_ENCRYPTION=tls
  ```

---
*Documento generado para el Sistema de Gestión de Turnos UNSa.*
