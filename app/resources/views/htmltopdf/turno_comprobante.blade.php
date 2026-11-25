<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Comprobante de Turno {{ $turno_reserva->codigo }}</title>
	<style>
		body {
			font-family: "DejaVu Sans", Arial, Helvetica, sans-serif;
			font-size: 11.5px;
			color: #1e293b;
			margin: 0;
			padding: 0;
			background: #ffffff;
		}
		table {
			border-collapse: collapse;
		}
		.sheet {
			width: 100%;
			max-width: 700px;
			margin: 0 auto;
			border: 1px solid #cbd5e1;
			background: #ffffff;
		}
		.top-accent {
			width: 100%;
			height: 6px;
			background-color: #0f172a;
		}
		.header-bar {
			width: 100%;
			background-color: #ffffff;
			border-bottom: 2px solid #e2e8f0;
		}
		.header-bar td {
			padding: 16px 22px;
			vertical-align: middle;
		}
		.org-top {
			font-size: 9.5px;
			letter-spacing: 1px;
			text-transform: uppercase;
			color: #0284c7;
			margin: 0 0 2px 0;
			font-weight: bold;
		}
		.org-title {
			font-size: 15.5px;
			font-weight: bold;
			color: #0f172a;
			margin: 0 0 2px 0;
		}
		.org-sub {
			font-size: 9.5px;
			color: #64748b;
			margin: 0;
		}
		.doc-tag {
			font-size: 9.5px;
			letter-spacing: 0.8px;
			text-transform: uppercase;
			color: #64748b;
			margin: 0 0 6px 0;
			text-align: right;
			font-weight: bold;
		}
		.status-badge {
			display: inline-block;
			padding: 4px 11px;
			border-radius: 12px;
			font-size: 9.5px;
			font-weight: bold;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			border: 1px solid transparent;
		}
		.ticket-strip {
			width: 100%;
			background-color: #f8fafc;
			border-bottom: 1px dashed #94a3b8;
		}
		.ticket-strip td {
			padding: 16px 22px;
			vertical-align: middle;
		}
		.code-label {
			font-size: 9px;
			font-weight: bold;
			text-transform: uppercase;
			letter-spacing: 1px;
			color: #0369a1;
			margin: 0 0 3px 0;
		}
		.code-value {
			font-family: "DejaVu Sans Mono", "Courier New", monospace;
			font-size: 25px;
			font-weight: bold;
			letter-spacing: 2.5px;
			color: #0f172a;
			margin: 0 0 4px 0;
		}
		.verif-seal {
			font-family: "DejaVu Sans Mono", "Courier New", monospace;
			font-size: 9px;
			color: #64748b;
			margin: 0;
		}
		.datetime-box {
			background-color: #ffffff;
			border: 1.5px solid #cbd5e1;
			border-radius: 6px;
			padding: 10px 14px;
			text-align: right;
		}
		.datetime-label {
			font-size: 9px;
			font-weight: bold;
			text-transform: uppercase;
			letter-spacing: 0.8px;
			color: #64748b;
			margin: 0 0 3px 0;
		}
		.datetime-date {
			font-size: 15px;
			font-weight: bold;
			color: #0f172a;
			margin: 0 0 2px 0;
		}
		.datetime-time {
			font-size: 14px;
			font-weight: bold;
			color: #0284c7;
			margin: 0;
		}
		.state-note {
			width: 100%;
			margin-bottom: 14px;
			border: 1px solid #e2e8f0;
		}
		.state-note td {
			padding: 8px 12px;
			font-size: 10.5px;
			line-height: 1.45;
		}
		.content-wrap {
			padding: 18px 22px 12px 22px;
		}
		.section-title {
			font-size: 10.5px;
			font-weight: bold;
			text-transform: uppercase;
			letter-spacing: 0.8px;
			color: #0f172a;
			border-bottom: 1.5px solid #0284c7;
			padding-bottom: 4px;
			margin: 0 0 9px 0;
		}
		.info-table {
			width: 100%;
			margin-bottom: 16px;
		}
		.info-table td {
			padding: 7px 10px;
			border: 1px solid #e2e8f0;
			vertical-align: top;
			width: 50%;
		}
		.field-label {
			font-size: 8.5px;
			font-weight: bold;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			color: #64748b;
			display: block;
			margin-bottom: 2px;
		}
		.field-value {
			font-size: 11.5px;
			font-weight: bold;
			color: #0f172a;
		}
		.nomina-table {
			width: 100%;
			margin-bottom: 16px;
			font-size: 10px;
		}
		.nomina-table th {
			background-color: #f1f5f9;
			color: #334155;
			border: 1px solid #cbd5e1;
			padding: 5px 8px;
			text-align: left;
			font-size: 9px;
			text-transform: uppercase;
		}
		.nomina-table td {
			border: 1px solid #e2e8f0;
			padding: 4px 8px;
			color: #1e293b;
		}
		.notice-box {
			width: 100%;
			background-color: #f8fafc;
			border: 1px solid #cbd5e1;
			border-left: 4px solid #0284c7;
			margin-bottom: 14px;
		}
		.notice-box td {
			padding: 10px 14px;
		}
		.notice-title {
			font-size: 10px;
			font-weight: bold;
			color: #0f172a;
			margin: 0 0 5px 0;
			text-transform: uppercase;
			letter-spacing: 0.4px;
		}
		.notice-list {
			margin: 0;
			padding-left: 16px;
			color: #334155;
			font-size: 10.5px;
			line-height: 1.5;
		}
		.notice-list li {
			margin-bottom: 2px;
		}
		.footer-bar {
			width: 100%;
			background-color: #f8fafc;
			border-top: 1px solid #e2e8f0;
		}
		.footer-bar td {
			padding: 10px 22px;
			font-size: 9.5px;
			color: #64748b;
		}
	</style>
</head>
<body>
@php
	$logoPath = public_path('img/logounsa.png');
	$logoSrc = '';
	if (file_exists($logoPath)) {
		$logoSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
	}

	$tramiteObj = null;
	if ($turno_reserva->turno_tramite && $turno_reserva->turno_tramite->tramite) {
		$tramiteObj = $turno_reserva->turno_tramite->tramite;
	} elseif ($turno_reserva->turno_horario && $turno_reserva->turno_horario->turno_tramite && $turno_reserva->turno_horario->turno_tramite->tramite) {
		$tramiteObj = $turno_reserva->turno_horario->turno_tramite->tramite;
	}
	$dependenciaNombre = ($tramiteObj && $tramiteObj->dependencia) ? $tramiteObj->dependencia->nombre : 'Universidad Nacional de Salta';
	$tramiteNombre = $tramiteObj ? $tramiteObj->nombre : 'Turno Reservado';

	$fechaSolo = $turno_reserva->fecha_hora ? $turno_reserva->fecha_hora->format('d/m/Y') : ($turno_reserva->fecha ? $turno_reserva->fecha->format('d/m/Y') : '-');
	$horaSolo = $turno_reserva->fecha_hora ? $turno_reserva->fecha_hora->format('H:i') : substr($turno_reserva->hora ?? '', 0, 5);

	if ($turno_reserva->estado_id == 4) {
		$estadoTexto = 'Cancelado';
		$estadoBg = '#fee2e2';
		$estadoBorder = '#fca5a5';
		$estadoColor = '#991b1b';
		$estadoNota = 'Esta reserva se encuentra cancelada y el cupo fue liberado.' . ($turno_reserva->motivo_cancelacion ? ' Motivo: ' . $turno_reserva->motivo_cancelacion : '');
	} elseif ($turno_reserva->estado_id == 3) {
		$estadoTexto = 'Confirmado';
		$estadoBg = '#dcfce7';
		$estadoBorder = '#86efac';
		$estadoColor = '#166534';
		$estadoNota = 'Reserva confirmada oficialmente por el equipo del Observatorio. Presentá este comprobante junto con tu DNI al ingresar.';
	} elseif ($turno_reserva->estado_id == 2) {
		$estadoTexto = 'Finalizado';
		$estadoBg = '#f1f5f9';
		$estadoBorder = '#cbd5e1';
		$estadoColor = '#334155';
		$estadoNota = 'Turno finalizado.';
	} else {
		$estadoTexto = 'Pendiente de Confirmación';
		$estadoBg = '#fef3c7';
		$estadoBorder = '#fcd34d';
		$estadoColor = '#92400e';
		$estadoNota = 'Tu cupo ya está reservado. Recibirás un correo electrónico cuando el equipo confirme formalmente la visita.';
	}

	if ($turno_reserva->nombre_institucion) {
		$modalidadTexto = 'Institucional / Escuela';
	} elseif ($turno_reserva->es_grupal) {
		$modalidadTexto = 'Grupal';
	} else {
		$modalidadTexto = 'Individual';
	}

	$cantPersonas = (int) ($turno_reserva->cantidad_personas ?: 1);
	$cantAcomp = (int) ($turno_reserva->cantidad_acompanantes ?: 0);
	$integrantes = $turno_reserva->integrantes ?? collect();
	$integrantesCount = $integrantes->count();
	$selloVerificacion = method_exists($turno_reserva, 'codigoVerificacion') ? $turno_reserva->codigoVerificacion() : '';
@endphp

<div class="sheet">
	<div class="top-accent"></div>

	<!-- Encabezado Institucional apto para pantalla e impresión -->
	<table class="header-bar">
		<tr>
			<td style="width: 66%;">
				<table>
					<tr>
						@if($logoSrc)
						<td style="padding: 0 12px 0 0; vertical-align: middle;">
							<img src="{{ $logoSrc }}" alt="UNSa" style="height: 48px; width: auto; display: block;">
						</td>
						@endif
						<td style="padding: 0; vertical-align: middle;">
							<p class="org-top">Universidad Nacional de Salta</p>
							<p class="org-title">Observatorio Astronómico Prof. Elvio Alanís</p>
							<p class="org-sub">Facultad de Ciencias Exactas &middot; Departamento de Física</p>
						</td>
					</tr>
				</table>
			</td>
			<td style="width: 34%; text-align: right;">
				<p class="doc-tag">Comprobante de Turno</p>
				<span class="status-badge" style="background-color: {{ $estadoBg }}; color: {{ $estadoColor }}; border-color: {{ $estadoBorder }};">{{ $estadoTexto }}</span>
			</td>
		</tr>
	</table>

	<!-- Franja Código de Reserva, Sello de Verificación y Fecha/Hora -->
	<table class="ticket-strip">
		<tr>
			<td style="width: 58%;">
				<p class="code-label">Código de Reserva</p>
				<p class="code-value">{{ $turno_reserva->codigo }}</p>
				@if($selloVerificacion)
					<p class="verif-seal">Sello de verificación: {{ $selloVerificacion }}</p>
				@endif
			</td>
			<td style="width: 42%;">
				<div class="datetime-box">
					<p class="datetime-label">Día y Horario Asignado</p>
					<p class="datetime-date">{{ $fechaSolo }}</p>
					<p class="datetime-time">{{ $horaSolo }} hs</p>
				</div>
			</td>
		</tr>
	</table>

	<!-- Cuerpo Principal -->
	<div class="content-wrap">
		<!-- Estado actual del circuito -->
		<table class="state-note" style="background-color: {{ $estadoBg }}; border-color: {{ $estadoBorder }};">
			<tr>
				<td style="color: {{ $estadoColor }};">
					<strong>Estado actual ({{ $estadoTexto }}):</strong> {{ $estadoNota }}
				</td>
			</tr>
		</table>

		<!-- 1. Datos de la Reserva -->
		<p class="section-title">1. Detalle del Turno</p>
		<table class="info-table">
			<tr>
				<td>
					<span class="field-label">Espacio / Dependencia</span>
					<span class="field-value">{{ $dependenciaNombre }}</span>
				</td>
				<td>
					<span class="field-label">Actividad / Trámite</span>
					<span class="field-value">{{ $tramiteNombre }}</span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="field-label">Modalidad</span>
					<span class="field-value">{{ $modalidadTexto }}</span>
				</td>
				<td>
					<span class="field-label">Cantidad de Asistentes</span>
					<span class="field-value">
						{{ $cantPersonas }} {{ $cantPersonas === 1 ? 'persona' : 'personas' }}
						@if($cantAcomp > 0)
							(+ {{ $cantAcomp }} {{ $cantAcomp === 1 ? 'acompañante' : 'acompañantes' }})
						@endif
					</span>
				</td>
			</tr>
			@if($turno_reserva->nombre_institucion)
			<tr>
				<td colspan="2">
					<span class="field-label">Institución / Escuela / Cátedra</span>
					<span class="field-value">
						{{ $turno_reserva->nombre_institucion }}
						@if($turno_reserva->nivel_institucion) &middot; {{ $turno_reserva->nivel_institucion }} @endif
						@if($turno_reserva->curso_comision) &middot; {{ $turno_reserva->curso_comision }} @endif
					</span>
				</td>
			</tr>
			@endif
		</table>

		<!-- 2. Datos del Titular / Responsable -->
		<p class="section-title">2. Datos del Titular / Responsable</p>
		<table class="info-table">
			<tr>
				<td>
					<span class="field-label">Nombre y Apellido</span>
					<span class="field-value">{{ mb_strtoupper($turno_reserva->nombre_apellido, 'UTF-8') }}</span>
					@if($turno_reserva->cargo_responsable)
						<br><span style="font-size: 9.5px; color: #64748b;">Cargo: {{ $turno_reserva->cargo_responsable }}</span>
					@endif
				</td>
				<td>
					<span class="field-label">DNI / Documento</span>
					<span class="field-value">{{ $turno_reserva->dni }}</span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="field-label">Teléfono / Celular</span>
					<span class="field-value">{{ $turno_reserva->celular ?: '-' }}</span>
				</td>
				<td>
					<span class="field-label">Correo Electrónico</span>
					<span class="field-value" style="word-break: break-all;">{{ $turno_reserva->email }}</span>
				</td>
			</tr>
			@if($turno_reserva->es_grupal || $turno_reserva->nombre_institucion)
			<tr>
				<td colspan="2">
					<span class="field-label">Nómina de Integrantes</span>
					<span class="field-value">
						@if($integrantesCount > 0)
							{{ $integrantesCount }} de {{ $cantPersonas }} integrantes registrados (ver detalle debajo).
						@else
							Pendiente de carga (puede completarse desde el portal ingresando código y DNI).
						@endif
					</span>
				</td>
			</tr>
			@endif
		</table>

		<!-- 3. Nómina adjunta (si corresponde y tiene integrantes cargados) -->
		@if($integrantesCount > 0)
			<p class="section-title">3. Nómina de Integrantes Registrados ({{ $integrantesCount }})</p>
			<table class="nomina-table">
				<thead>
					<tr>
						<th style="width: 8%;">#</th>
						<th style="width: 62%;">Apellido y Nombre</th>
						<th style="width: 30%;">DNI</th>
					</tr>
				</thead>
				<tbody>
					@foreach($integrantes as $idx => $int)
						<tr>
							<td>{{ $idx + 1 }}</td>
							<td>{{ trim(($int->apellido ?? '') . ' ' . ($int->nombre ?? '')) }}</td>
							<td>{{ $int->dni ?? '-' }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@endif

		<!-- Recomendaciones prácticas -->
		<table class="notice-box">
			<tr>
				<td>
					<p class="notice-title">Información útil para el día de tu visita</p>
					<ul class="notice-list">
						<li><strong>Acreditación:</strong> Presentá este comprobante digital o impreso junto con tu DNI al ingresar.</li>
						<li><strong>Ubicación:</strong> Av. Bolivia 5150, Campus Castañares, Salta (Facultad de Ciencias Exactas, UNSa). Llegar 10 a 15 minutos antes.</li>
						<li><strong>Gestión y cancelación:</strong> Para consultar o cancelar tu reserva necesitarás tu <strong>código ({{ $turno_reserva->codigo }})</strong> y tu <strong>DNI</strong>.</li>
					</ul>
				</td>
			</tr>
		</table>
	</div>

	<!-- Pie de página -->
	<table class="footer-bar">
		<tr>
			<td style="width: 60%;">
				Contacto: <strong>observatorioalanis@exa.unsa.edu.ar</strong>
			</td>
			<td style="width: 40%; text-align: right;">
				Emitido el {{ date('d/m/Y H:i') }} hs
			</td>
		</tr>
	</table>
</div>
</body>
</html>