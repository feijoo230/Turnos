<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Reserva de Turno</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; color: #333333; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e1e8ed; }
        .header { text-align: center; border-bottom: 2px solid #16a34a; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { color: #16a34a; margin: 0; }
        .badge-code { background-color: #1e3c72; color: #ffffff; padding: 6px 12px; border-radius: 4px; font-size: 1.1rem; font-weight: bold; display: inline-block; margin-top: 4px; }
        .details { background-color: #f8fafc; padding: 18px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #16a34a; }
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
        <p>Le informamos que su reserva de turno ha sido <strong>confirmada exitosamente</strong>.</p>
        
        <div class="details">
            <p><strong>Código de Reserva:</strong> <br><span class="badge-code">{{ $reserva->codigo }}</span></p>
            <p><strong>Fecha y Hora:</strong> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} - {{ $reserva->hora }} hs</p>
            <p><strong>Trámite / Servicio:</strong> {{ $reserva->turno_horario->turno_tramite->tramite->nombre ?? 'Trámite Solicitado' }}</p>
            <p><strong>Dependencia:</strong> {{ $reserva->turno_horario->turno_tramite->tramite->dependencia->nombre ?? 'UNSa' }}</p>
            @if($reserva->nombre_institucion)
                <p><strong>Institución / Delegación:</strong> {{ $reserva->nombre_institucion }}</p>
            @endif
            @if($reserva->cantidad_personas > 1)
                <p><strong>Cantidad de Asistentes:</strong> {{ $reserva->cantidad_personas }} personas</p>
            @endif
        </div>

        <p>Por favor, presente este código al momento de su acreditación o atención.</p>

        <div class="footer">
            <p>Sistema de Gestión de Turnos - Universidad Nacional de Salta</p>
            <p>Este es un correo automático, por favor no responda directamente a este mensaje.</p>
        </div>
    </div>
</body>
</html>
