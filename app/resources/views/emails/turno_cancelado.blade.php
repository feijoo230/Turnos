<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cancelación de Turno</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; color: #333333; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e1e8ed; }
        .header { text-align: center; border-bottom: 2px solid #dc2626; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { color: #dc2626; margin: 0; }
        .details { background-color: #f8fafc; padding: 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #dc2626; }
        .details p { margin: 6px 0; }
        .reason-box { background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px; border-radius: 6px; margin-top: 15px; }
        .footer { text-align: center; font-size: 0.85rem; color: #6b7280; margin-top: 25px; border-top: 1px solid #e5e7eb; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Cancelación de Reserva de Turno</h2>
        </div>
        <p>Estimado/a <strong>{{ $reserva->nombre_apellido }}</strong>,</p>
        <p>Le informamos que su reserva de turno ha sido <strong>cancelada correctamente</strong>.</p>
        
        <div class="details">
            <p><strong>Código de Reserva:</strong> {{ $reserva->codigo }}</p>
            <p><strong>Fecha y Hora:</strong> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} - {{ $reserva->hora }} hs</p>
            <p><strong>Trámite / Servicio:</strong> {{ $reserva->turno_horario->turno_tramite->tramite->nombre ?? 'Trámite Solicitado' }}</p>
            <p><strong>Dependencia:</strong> {{ $reserva->turno_horario->turno_tramite->tramite->dependencia->nombre ?? 'UNSa' }}</p>
        </div>

        @if($reserva->motivo_cancelacion)
        <div class="reason-box">
            <strong>Motivo registrado para la cancelación:</strong>
            <p style="margin: 5px 0 0 0;">{{ $reserva->motivo_cancelacion }}</p>
        </div>
        @endif

        <p style="margin-top: 20px;">El cupo correspondiente ha sido re-habilitado en nuestro sistema público para que otros usuarios puedan solicitarlo.</p>
        <p>Si requiere una nueva atención, puede ingresar nuevamente al sistema y solicitar un nuevo turno en el horario de su preferencia.</p>

        <div class="footer">
            <p>Sistema de Gestión de Turnos - Universidad Nacional de Salta</p>
            <p>Este es un correo automático, por favor no responda directamente a este mensaje.</p>
        </div>
    </div>
</body>
</html>
