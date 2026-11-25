@extends('layouts.frontend')
@section('content')

<div class="row justify-content-center">
	<div class="col-lg-10 col-xl-9">
		<div class="card box-turno">
			<div class="card-body p-4 text-center">
				<!-- Stepper completado -->
				<div class="wizard-steps mb-4">
					<div class="wizard-step-item completed">
						<div class="wizard-step-circle"><i class="fas fa-check"></i></div>
						<span class="wizard-step-title">1. Actividad</span>
					</div>
					<div class="wizard-step-item completed">
						<div class="wizard-step-circle"><i class="fas fa-check"></i></div>
						<span class="wizard-step-title">2. Día y Hora</span>
					</div>
					<div class="wizard-step-item completed">
						<div class="wizard-step-circle"><i class="fas fa-check"></i></div>
						<span class="wizard-step-title">3. ¡Listo!</span>
					</div>
				</div>

				<!-- Icono y Título de Éxito -->
				<div class="mb-4">
					<div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px; background: #dcfce7;">
						<i class="fas fa-check-circle fa-3x text-success"></i>
					</div>
					<h3 class="font-weight-bold text-dark mb-1">¡Listo! Tu Solicitud de Turno Quedó Registrada</h3>
					<p class="text-muted mb-2">
						Tu cupo ya está reservado y enviamos los datos a <strong>{{ $turno_reserva->email }}</strong>.
					</p>
					@if($turno_reserva->estado_id == 3)
						<span class="badge px-3 py-2" style="background: #dcfce7; color: #166534; font-size: 0.85rem; border-radius: 20px;">
							<i class="fas fa-check-circle mr-1"></i> Estado: Confirmado
						</span>
					@else
						<span class="badge px-3 py-2" style="background: #fef3c7; color: #92400e; font-size: 0.85rem; border-radius: 20px;">
							<i class="fas fa-clock mr-1"></i> Estado: Pendiente de confirmación (recibirás un correo al aprobarse)
						</span>
					@endif
				</div>

				<!-- Tarjeta Destacada Código de Reserva -->
				<div class="p-4 mb-4 text-white rounded-lg shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e3c72 60%, #0284c7 100%); border-radius: 16px;">
					<small class="text-uppercase font-weight-bold d-block mb-1" style="letter-spacing: 1.2px; opacity: 0.85;">Tu Código de Reserva Oficial</small>
					<h2 id="codigo-reserva-texto" class="font-weight-bold text-monospace mb-3" style="font-size: 2.3rem; letter-spacing: 3px; color: #ffffff !important;">{{ $turno_reserva->codigo }}</h2>
					<div class="d-flex justify-content-center flex-wrap" style="gap: 10px;">
						<a href="{!! route('turnos.print', [$turno_reserva->id]) !!}" class="btn btn-light text-danger btn-lg font-weight-bold px-4 shadow" target="_blank" style="border-radius: 10px;">
							<i class="fas fa-file-pdf mr-2"></i> Descargar Comprobante PDF
						</a>
						<button type="button" id="btn-copiar-codigo" class="btn btn-outline-light btn-lg font-weight-bold px-3" style="border-radius: 10px;" onclick="copiarCodigoReserva()">
							<i class="far fa-copy mr-1"></i> Copiar Código
						</button>
					</div>
				</div>

				<!-- Detalle de la Reserva -->
				<div class="p-4 bg-light rounded-lg border text-left text-start mb-4" style="border-radius: 14px; border-color: #e2e8f0 !important;">
					<h5 class="font-weight-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-receipt text-primary mr-2"></i>Resumen de tu Reserva</h5>
					
					<div class="row">
						@if ($turno_reserva->turno_tramite && $turno_reserva->turno_tramite->tramite && $turno_reserva->turno_tramite->tramite->dependencia)
						<div class="col-md-6 col-12 mb-3">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.72rem;">Espacio / Oficina</small>
							<span class="font-weight-bold text-dark" style="font-size: 1rem;">
								<i class="fas fa-university text-primary mr-1"></i> {{ $turno_reserva->turno_tramite->tramite->dependencia->nombre }}
							</span>
						</div>
						@endif

						@if ($turno_reserva->turno_tramite && $turno_reserva->turno_tramite->tramite)
						<div class="col-md-6 col-12 mb-3">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.72rem;">Visita / Actividad</small>
							<span class="font-weight-bold text-dark" style="font-size: 1rem;">
								<i class="fas fa-star text-info mr-1"></i> {{ $turno_reserva->turno_tramite->tramite->nombre }}
							</span>
						</div>
						@endif
					</div>

					<div class="row">
						<div class="col-md-6 col-12 mb-3">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.72rem;">Día y Horario</small>
							<span class="font-weight-bold text-success" style="font-size: 1.08rem;">
								<i class="fas fa-calendar-check mr-1"></i> {{ $turno_reserva->fecha_hora->format('d/m/Y H:i') }} hs
							</span>
						</div>

						<div class="col-md-6 col-12 mb-3">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.72rem;">Titular / Responsable</small>
							<span class="font-weight-bold text-dark" style="font-size: 1rem;">
								<i class="fas fa-user text-secondary mr-1"></i> {{ $turno_reserva->nombre_apellido }}
							</span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 col-12 mb-3 mb-md-0">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.72rem;">DNI</small>
							<span class="font-weight-bold text-dark" style="font-size: 0.98rem;">
								<i class="fas fa-id-card text-secondary mr-1"></i> {{ $turno_reserva->dni }}
							</span>
						</div>

						<div class="col-md-4 col-12 mb-3 mb-md-0">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.72rem;">Teléfono</small>
							<span class="font-weight-bold text-dark" style="font-size: 0.98rem;">
								<i class="fas fa-phone-alt text-secondary mr-1"></i> {{ $turno_reserva->celular ?: '-' }}
							</span>
						</div>

						<div class="col-md-4 col-12">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.72rem;">Correo Electrónico</small>
							<span class="font-weight-bold text-dark" style="font-size: 0.95rem; word-break: break-all;">
								<i class="fas fa-envelope text-secondary mr-1"></i> {{ $turno_reserva->email }}
							</span>
						</div>
					</div>

					<hr class="my-3">
					<div class="row">
						<div class="col-md-6 col-12 mb-3">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.72rem;">Tipo de Reserva</small>
							@if($turno_reserva->nombre_institucion)
								<span class="badge px-3 py-2 text-white font-weight-bold" style="font-size: 0.88rem; background-color: #2563eb; color: #ffffff !important;"><i class="fas fa-school mr-1"></i> Institucional / Escuela</span>
							@elseif($turno_reserva->es_grupal)
								<span class="badge px-3 py-2 text-white font-weight-bold" style="font-size: 0.88rem; background-color: #0284c7; color: #ffffff !important;"><i class="fas fa-users mr-1"></i> Grupal</span>
							@else
								<span class="badge px-3 py-2 text-white font-weight-bold" style="font-size: 0.88rem; background-color: #475569; color: #ffffff !important;"><i class="fas fa-user mr-1"></i> Individual</span>
							@endif
						</div>
						<div class="col-md-6 col-12 mb-3">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.72rem;">Cantidad de Asistentes</small>
							<span class="font-weight-bold text-dark" style="font-size: 1rem;">
								{{ $turno_reserva->cantidad_personas }} {{ $turno_reserva->cantidad_personas == 1 ? 'persona' : 'personas' }}
								@if($turno_reserva->cantidad_acompanantes > 0)
									<span class="text-muted font-weight-normal">(+ {{ $turno_reserva->cantidad_acompanantes }} acompañante/s)</span>
								@endif
							</span>
						</div>
						@if($turno_reserva->nombre_institucion)
						<div class="col-12 mt-1 mb-2">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.72rem;">Institución / Colegio</small>
							<span class="font-weight-bold text-dark" style="font-size: 1rem;">
								<i class="fas fa-school text-primary mr-1"></i> {{ $turno_reserva->nombre_institucion }}
								@if($turno_reserva->nivel_institucion) ({{ $turno_reserva->nivel_institucion }}) @endif
								@if($turno_reserva->curso_comision) &middot; {{ $turno_reserva->curso_comision }} @endif
							</span>
						</div>
						@endif
					</div>

					@if($turno_reserva->es_grupal || $turno_reserva->nombre_institucion)
					<div class="alert alert-info mt-2 mb-0 border-0 shadow-xs text-left" style="border-radius: 10px; background: #e0f2fe; color: #0369a1;">
						<div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
							<div>
								<strong class="d-block"><i class="fas fa-users mr-1"></i> ¿Necesitás cargar o actualizar la lista de asistentes?</strong>
								<small class="d-block">Podés escribir los nombres directo en pantalla o subir un archivo Excel en cualquier momento con tu código.</small>
							</div>
							<a href="{!! route('tramite.integrantes.form', [$turno_reserva->codigo]) !!}" class="btn btn-primary btn-sm font-weight-bold shadow-sm" style="border-radius: 8px;">
								<i class="fas fa-user-plus mr-1"></i> Cargar / Ver Integrantes
							</a>
						</div>
					</div>
					@endif
				</div>

				<!-- Qué hacer ahora (guía clara para el visitante) -->
				<div class="p-3 mb-4 text-left text-start" style="background: #fffbeb; border: 1px solid #fde68a; border-left: 4px solid #f59e0b; border-radius: 12px; color: #78350f;">
					<h6 class="font-weight-bold mb-2" style="color: #92400e;"><i class="fas fa-clipboard-list mr-2"></i>Recomendaciones para el día de tu visita</h6>
					<ul class="mb-0 pl-3 small" style="line-height: 1.7;">
						<li><strong>Mostrá tu comprobante:</strong> podés llevarlo descargado en el celular o impreso, junto con tu DNI.</li>
						<li><strong>Llegá 10 a 15 minutos antes:</strong> Campus Castañares, Av. Bolivia 5150 (Facultad de Ciencias Exactas, UNSa).</li>
						<li><strong>¿No podés asistir?</strong> Podés cancelar tu reserva desde <em>Consultar Turno</em> ingresando tu <strong>código de reserva y DNI</strong> para liberar el lugar a otra persona.</li>
					</ul>
				</div>

				<!-- Botonera Final -->
				<div class="d-flex justify-content-center flex-wrap" style="gap: 12px;">
					<a href="{!! route('turnos.print', [$turno_reserva->id]) !!}" class="btn btn-outline-danger font-weight-bold" target="_blank" style="border-radius: 10px; padding: 10px 22px;">
						<i class="fas fa-file-pdf mr-1"></i> Descargar Comprobante PDF
					</a>
					<a href="{!! route('turnos') !!}" class="btn btn-gradient-primary font-weight-bold px-4" style="border-radius: 10px; padding: 10px 24px;">
						<i class="fas fa-plus-circle mr-1"></i> Reservar Otro Turno
					</a>
					<a href="{!! url('/') !!}" class="btn btn-outline-secondary font-weight-bold" style="border-radius: 10px; padding: 10px 20px;">
						<i class="fas fa-home mr-1"></i> Ir al Observatorio
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

@stop

@section('script')
<script>
	function copiarCodigoReserva() {
		var codigo = document.getElementById('codigo-reserva-texto').innerText.trim();
		var btn = document.getElementById('btn-copiar-codigo');

		function marcarCopiado() {
			btn.innerHTML = '<i class="fas fa-check mr-1"></i> ¡Código copiado!';
			btn.classList.remove('btn-outline-light');
			btn.classList.add('btn-success');
			setTimeout(function() {
				btn.innerHTML = '<i class="far fa-copy mr-1"></i> Copiar Código';
				btn.classList.remove('btn-success');
				btn.classList.add('btn-outline-light');
			}, 2500);
		}

		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(codigo).then(marcarCopiado);
		} else {
			var tmp = document.createElement('input');
			tmp.value = codigo;
			document.body.appendChild(tmp);
			tmp.select();
			document.execCommand('copy');
			document.body.removeChild(tmp);
			marcarCopiado();
		}
	}
</script>
@stop