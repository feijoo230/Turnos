@extends('layouts.frontend')
@section('content')

<div class="row justify-content-center">
	<div class="col-lg-10 col-xl-9">
		<div class="card box-turno">
			<div class="card-body p-4">
				<!-- Stepper Wizard -->
				<div class="wizard-steps mb-4">
					<div class="wizard-step-item">
						<div class="wizard-step-circle"><i class="fas fa-check text-success"></i></div>
						<span class="wizard-step-title">1. Selección</span>
					</div>
					<div class="wizard-step-item">
						<div class="wizard-step-circle"><i class="fas fa-check text-success"></i></div>
						<span class="wizard-step-title">2. Fecha y Hora</span>
					</div>
					<div class="wizard-step-item active">
						<div class="wizard-step-circle"><i class="fas fa-id-card"></i></div>
						<span class="wizard-step-title">3. Confirmación</span>
					</div>
				</div>

				<div class="text-center mb-4">
					<h4 class="font-weight-bold text-dark mb-1"><i class="fas fa-user-check text-primary mr-2"></i>Complete sus Datos Personales</h4>
					<p class="text-muted small mb-0">Revise la reserva y complete sus datos para emitir su comprobante oficial.</p>
				</div>

				<!-- Resumen de la reserva elegida -->
				<div class="p-3 mb-4 rounded-lg border-0 shadow-xs" style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border-radius: 12px;">
					<div class="row align-items-center">
						<div class="col-md-6 col-12 mb-2 mb-md-0">
							<small class="text-uppercase text-primary font-weight-bold" style="letter-spacing: 0.5px;">Dependencia Solicitada</small>
							<h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-university text-primary mr-1"></i> {{$dependencia['nombre']}}</h6>
						</div>
						<div class="col-md-6 col-12 text-md-right">
							<small class="text-uppercase text-success font-weight-bold" style="letter-spacing: 0.5px;">Fecha y Hora Asignada</small>
							<h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-calendar-check text-success mr-1"></i> {{$turno_fecha}} - {{$turno_hora}} hs</h6>
						</div>
					</div>
				</div>

				@if ($errors->any())
					<div class="alert alert-danger shadow-sm border-0 mb-4" style="border-radius: 10px;">
						<strong class="d-block mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> Corrija los errores para continuar:</strong>
						<ul class="mb-0 pl-3">
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif

				{!! Form::open(['route' => 'tramite.guardar', 'method' => 'post', 'class' => 'form-horizontal', 'files' => true]) !!}
					<div class="row">
						<div class="col-md-6 col-12 mb-3">
							<label for="nombre_apellido" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-user mr-1"></i> Nombre y Apellido Completo *</label>
							{!! Form::text('nombre_apellido', isset($usuario) ? $usuario->name : null, ['class' => 'form-control form-control-lg', 'placeholder' => 'Ej: María González', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
						</div>

						<div class="col-md-6 col-12 mb-3">
							<label for="dni" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-id-card mr-1"></i> Número de DNI / Documento *</label>
							{!! Form::text('dni', isset($usuario) && $usuario->persona ? $usuario->persona->nro_documento : null, ['class' => 'form-control form-control-lg', 'placeholder' => 'Ej: 38123456', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
						</div>
					</div>

					<div class="row">
						<div class="col-md-6 col-12 mb-3">
							<label for="celular" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-phone-alt mr-1"></i> Teléfono Móvil / Celular *</label>
							{!! Form::text('celular', isset($usuario) && $usuario->persona ? $usuario->persona->tel_movil : null, ['class' => 'form-control form-control-lg', 'placeholder' => 'Ej: 3874123456', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
						</div>

						<div class="col-md-6 col-12 mb-3">
							<label for="email" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-envelope mr-1"></i> Correo Electrónico *</label>
							{!! Form::email('email', isset($usuario) ? $usuario->email : null, ['class' => 'form-control form-control-lg', 'placeholder' => 'Ej: usuario@unsa.edu.ar', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
						</div>
					</div>

					<div class="row">
						<div class="col-md-6 col-12 mb-3">
							<label for="email_confirmation" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-check-double mr-1"></i> Confirmar Correo Electrónico *</label>
							{!! Form::email('email_confirmation', isset($usuario) ? $usuario->email : null, ['class' => 'form-control form-control-lg', 'placeholder' => 'Reingrese su correo electrónico', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
						</div>
					</div>

					<!-- Sección Reserva Grupal -->
					<div class="form-group my-3">
						<div class="custom-control custom-checkbox p-3 bg-light rounded border border-light">
							<input class="custom-control-input" type="checkbox" name="es_grupal" id="es_grupal" value="1">
							<label class="custom-control-label font-weight-bold text-primary cursor-pointer mb-0" for="es_grupal">
								<i class="fas fa-users mr-1"></i> ¿Desea realizar una Reserva Grupal o Institucional?
							</label>
							<small class="d-block text-muted mt-1">Marque esta opción si asiste en representación de un colegio, cátedra o grupo.</small>
						</div>
					</div>

					<div id="campos_grupales" style="display: none; background: #f8fafc; padding: 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #cbd5e1;">
						<h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-user-friends text-info mr-2"></i>Datos del Grupo de Asistentes</h6>
						
						<div class="row">
							<div class="col-md-6 col-12 mb-3">
								<label for="cantidad_personas" class="small font-weight-bold">Cantidad Total de Asistentes *</label>
								{!! Form::number('cantidad_personas', 1, ['class' => 'form-control', 'placeholder' => 'Cantidad de personas', 'min' => 1, 'id' => 'cantidad_personas', 'style' => 'border-radius: 8px;']) !!}
							</div>
							<div class="col-md-6 col-12 mb-3">
								<label for="nombre_institucion" class="small font-weight-bold">Nombre de la Institución / Cátedra *</label>
								{!! Form::text('nombre_institucion', null, ['class' => 'form-control', 'placeholder' => 'Ej: Colegio Nacional No. 1', 'style' => 'border-radius: 8px;']) !!}
							</div>
						</div>

						<div class="form-group mb-0">
							<label for="archivo_integrantes" class="small font-weight-bold">Adjuntar Lista de Integrantes (Excel/CSV) *</label>
							{!! Form::file('archivo_integrantes', ['class' => 'form-control', 'accept' => '.xls,.xlsx,.csv', 'style' => 'border-radius: 8px; padding: 6px 12px;']) !!}
							<small class="form-text text-muted mt-1">
								<i class="fas fa-download text-primary mr-1"></i> <a href="{{ asset('plantilla_integrantes.xlsx') }}" download class="font-weight-bold text-primary">Descargar plantilla Excel de ejemplo</a> (columnas: nombre, apellido, dni).
							</small>
						</div>
					</div>

					<hr class="my-4">

					<div class="d-flex justify-content-between align-items-center">
						<button onclick="location.href='{!! route('tramite.paso2') !!}'" class="btn btn-outline-secondary font-weight-bold" type="button" style="border-radius: 10px; padding: 8px 20px;">
							<i class="fas fa-arrow-left mr-1"></i> Volver a Horarios
						</button>
						<button type="submit" class="btn btn-gradient-primary btn-lg shadow">
							<i class="fas fa-check-circle mr-1"></i> Confirmar y Reservar Turno
						</button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop

@section('script')
<script>
	$(document).ready(function() {
		$('#es_grupal').on('change', function() {
			if ($(this).is(':checked')) {
				$('#campos_grupales').slideDown(200);
				$('#cantidad_personas').val(2).attr('min', 2).attr('required', true);
				$('input[name="nombre_institucion"]').attr('required', true);
				$('input[name="archivo_integrantes"]').attr('required', true);
			} else {
				$('#campos_grupales').slideUp(200);
				$('#cantidad_personas').val(1).attr('min', 1).removeAttr('required');
				$('input[name="nombre_institucion"]').removeAttr('required');
				$('input[name="archivo_integrantes"]').removeAttr('required');
			}
		});
	});
</script>
@endsection