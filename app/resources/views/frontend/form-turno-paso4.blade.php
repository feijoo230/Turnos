@extends('layouts.frontend')
@section('content')

<div class="row justify-content-center">
	<div class="col-lg-10 col-xl-9">
		<div class="card box-turno">
			<div class="card-body p-4 text-center">
				<!-- Icono y Título de Éxito -->
				<div class="mb-4">
					<div class="rounded-circle bg-light-success text-success mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: #dcfce7;">
						<i class="fas fa-check-circle fa-3x text-success"></i>
					</div>
					<h3 class="font-weight-bold text-dark mb-1">¡El Turno se Registró con Éxito!</h3>
					<div class="alert alert-warning d-inline-block shadow-xs border-0 py-2 px-4 mt-2" style="border-radius: 30px; background: #fffbebf5; color: #b45309; border: 1px solid #fde68a !important;">
						<i class="fas fa-exclamation-triangle mr-1"></i> <strong>¡IMPORTANTE!</strong> Presente el comprobante de turno el día de su atención.
					</div>
				</div>

				<!-- Tarjeta Destacada Código de Reserva -->
				<div class="p-4 mb-4 text-white rounded-lg shadow-sm" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border-radius: 16px;">
					<small class="text-uppercase font-weight-bold opacity-80 d-block mb-1" style="letter-spacing: 1px;">Su Código de Reserva Oficial</small>
					<h2 class="font-weight-bold text-monospace mb-3" style="font-size: 2.2rem; letter-spacing: 2px;">{!! $turno_reserva->codigo !!}</h2>
					<div>
						<a href="{!! route('turnos.print', [$turno_reserva->id]) !!}" class="btn btn-danger btn-lg font-weight-bold px-4 shadow" target="_blank" style="border-radius: 10px;">
							<i class="fas fa-file-pdf mr-2"></i> Descargar Comprobante PDF
						</a>
					</div>
				</div>

				<!-- Detalle de la Reserva -->
				<div class="p-4 bg-light rounded-lg border border-light text-left text-start mb-4" style="border-radius: 14px;">
					<h5 class="font-weight-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-receipt text-primary mr-2"></i>Detalles de la Solicitud</h5>
					
					<div class="row">
						@if ($turno_reserva->turno_tramite && $turno_reserva->turno_tramite->tramite && $turno_reserva->turno_tramite->tramite->dependencia)
						<div class="col-md-6 col-12 mb-3">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.75rem;">Dirección / Oficina</small>
							<span class="font-weight-bold text-dark" style="font-size: 1rem;">
								<i class="fas fa-university text-primary mr-1"></i> {!! $turno_reserva->turno_tramite->tramite->dependencia->nombre !!}
							</span>
						</div>
						@endif

						@if ($turno_reserva->turno_tramite && $turno_reserva->turno_tramite->tramite)
						<div class="col-md-6 col-12 mb-3">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.75rem;">Trámite / Servicio</small>
							<span class="font-weight-bold text-dark" style="font-size: 1rem;">
								<i class="fas fa-list-alt text-info mr-1"></i> {!! $turno_reserva->turno_tramite->tramite->nombre !!}
							</span>
						</div>
						@endif
					</div>

					<div class="row">
						<div class="col-md-6 col-12 mb-3">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.75rem;">Fecha y Hora Asignada</small>
							<span class="font-weight-bold text-success" style="font-size: 1.05rem;">
								<i class="fas fa-calendar-check mr-1"></i> {!! $turno_reserva->fecha_hora->format('d/m/Y H:i') !!} hs
							</span>
						</div>

						<div class="col-md-6 col-12 mb-3">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.75rem;">Nombre y Apellido</small>
							<span class="font-weight-bold text-dark" style="font-size: 1rem;">
								<i class="fas fa-user text-secondary mr-1"></i> {!! $turno_reserva->nombre_apellido !!}
							</span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6 col-12 mb-3 mb-md-0">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.75rem;">Número de DNI</small>
							<span class="font-weight-bold text-dark" style="font-size: 1rem;">
								<i class="fas fa-id-card text-secondary mr-1"></i> {!! $turno_reserva->dni !!}
							</span>
						</div>

						<div class="col-md-6 col-12">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.75rem;">Correo Electrónico</small>
							<span class="font-weight-bold text-dark" style="font-size: 1rem;">
								<i class="fas fa-envelope text-secondary mr-1"></i> {!! $turno_reserva->email !!}
							</span>
						</div>
					</div>

					<hr class="my-3">
					<div class="row">
						<div class="col-md-6 col-12 mb-3">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.75rem;">Tipo de Reserva</small>
							@if($turno_reserva->nombre_institucion)
								<span class="badge px-3 py-2 text-white font-weight-bold" style="font-size: 0.9rem; background-color: #2563eb; color: #ffffff !important;"><i class="fas fa-university mr-1"></i> Reserva Institucional</span>
							@elseif($turno_reserva->es_grupal)
								<span class="badge px-3 py-2 text-white font-weight-bold" style="font-size: 0.9rem; background-color: #0284c7; color: #ffffff !important;"><i class="fas fa-users mr-1"></i> Reserva Grupal</span>
							@else
								<span class="badge px-3 py-2 text-white font-weight-bold" style="font-size: 0.9rem; background-color: #475569; color: #ffffff !important;"><i class="fas fa-user mr-1"></i> Reserva Individual</span>
							@endif
						</div>
						<div class="col-md-6 col-12 mb-3">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.75rem;">Cantidad de Asistentes</small>
							<span class="font-weight-bold text-dark" style="font-size: 1rem;">{!! $turno_reserva->cantidad_personas !!} persona(s)</span>
						</div>
						@if($turno_reserva->nombre_institucion)
						<div class="col-12 mt-1 mb-3">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.75rem;">Institución / Colegio</small>
							<span class="font-weight-bold text-dark" style="font-size: 1rem;">
								<i class="fas fa-school text-primary mr-1"></i> {!! $turno_reserva->nombre_institucion !!} @if($turno_reserva->nivel_institucion) ({!! $turno_reserva->nivel_institucion !!}) @endif
							</span>
						</div>
						@endif
					</div>

					@if($turno_reserva->es_grupal || $turno_reserva->nombre_institucion)
					<div class="alert alert-info mt-2 mb-0 border-0 shadow-xs text-left" style="border-radius: 10px; background: #e0f2fe; color: #0369a1;">
						<div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
							<div>
								<strong class="d-block"><i class="fas fa-file-excel mr-1"></i> ¿Necesita cargar o modificar la lista de integrantes para el seguro escolar?</strong>
								<small class="d-block">Puede ingresar en cualquier momento con su código de reserva desde la consulta de turno.</small>
							</div>
							<a href="{!! route('tramite.integrantes.form', [$turno_reserva->codigo]) !!}" class="btn btn-info btn-sm font-weight-bold shadow-sm" style="border-radius: 8px;">
								<i class="fas fa-upload mr-1"></i> Cargar / Ver Integrantes
							</a>
						</div>
					</div>
					@endif
				</div>

				<!-- Botonera Final -->
				<div class="d-flex justify-content-center" style="gap: 15px;">
					<a href="{!! route('turnos.print', [$turno_reserva->id]) !!}" class="btn btn-outline-danger font-weight-bold" target="_blank" style="border-radius: 10px; padding: 10px 22px;">
						<i class="fas fa-file-pdf mr-1"></i> Descargar Comprobante
					</a>
					<button onclick="location.href='{!! route('turnos') !!}'" class="btn btn-gradient-primary font-weight-bold px-4" type="button" style="border-radius: 10px; padding: 10px 24px;">
						<i class="fas fa-home mr-1"></i> Volver al Inicio
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

@stop