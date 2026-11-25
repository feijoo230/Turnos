<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Turno Registrada</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; color: #333333; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e1e8ed; }
        .header { text-align: center; border-bottom: 2px solid #0284c7; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { color: #0284c7; margin: 0; }
        .badge-code { background-color: #1e3c72; color: #ffffff; padding: 6px 14px; border-radius: 4px; font-size: 1.15rem; font-weight: bold; display: inline-block; margin-top: 4px; letter-spacing: 1px; }
        .details { background-color: #f8fafc; padding: 18px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #0284c7; }
        .details p { margin: 6px 0; }
        .footer { text-align: center; font-size: 0.85rem; color: #6b7280; margin-top: 25px; border-top: 1px solid #e5e7eb; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Solicitud de Turno Registrada</h2>
        </div>
        <p>Estimado/a <strong>{{ $reserva->nombre_apellido }}</strong>,</p>
        <p>Registramos tu solicitud y apartamos tu cupo en el sistema. Recibirás una nueva notificación cuando el equipo confirme tu visita.</p>

        <div class="details">
            <p><strong>Código de Reserva:</strong> <br><span class="badge-code">{{ $reserva->codigo }}</span></p>
            <p><strong>Fecha y Hora:</strong> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} - {{ substr($reserva->hora ?? '', 0, 5) }} hs</p>
            <p><strong>Actividad / Trámite:</strong> {{ $reserva->turno_horario->turno_tramite->tramite->nombre ?? 'Trámite Solicitado' }}</p>
            <p><strong>Dependencia:</strong> {{ $reserva->turno_horario->turno_tramite->tramite->dependencia->nombre ?? 'UNSa' }}</p>
            @if($reserva->nombre_institucion)
                <p><strong>Institución / Delegación:</strong> {{ $reserva->nombre_institucion }}</p>
            @endif
            @if($reserva->cantidad_personas > 1)
                <p><strong>Cantidad de Asistentes:</strong> {{ $reserva->cantidad_personas }} personas</p>
            @endif
        </div>

        <p>Guardá este código junto con tu DNI: los necesitarás para consultar tu turno, descargar el comprobante PDF o cancelar si no podés asistir.</p>

        <div class="footer">
            <p>Observatorio Astronómico Prof. Elvio Alanís &middot; Universidad Nacional de Salta</p>
            <p>Este es un correo automático, por favor no responda directamente a esta casilla.</p>
        </div>
    </div>
</body>
</html>
