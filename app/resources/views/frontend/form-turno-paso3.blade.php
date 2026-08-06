@extends('layouts.frontend')
@section('content')

@php
	$modalidad = isset($dependencia_tramite) ? ($dependencia_tramite->tipo_modalidad ?? 'individual') : 'individual';
	$isInstitucional = ($modalidad === 'institucional' || (isset($dependencia_tramite) && $dependencia_tramite->requiere_institucion));
	$isGrupal = ($modalidad === 'grupal');
	$isMixto = ($modalidad === 'mixto');
	$maxPersonas = isset($dependencia_tramite) && $dependencia_tramite->max_personas_reserva ? $dependencia_tramite->max_personas_reserva : 50;
	$minPersonas = isset($dependencia_tramite) && $dependencia_tramite->min_personas_reserva ? $dependencia_tramite->min_personas_reserva : 2;
	$requiereNomina = isset($dependencia_tramite) ? ($dependencia_tramite->requiere_nomina ?? false) : false;

	$activeModalidad = 'individual';
	if ($isInstitucional) {
		$activeModalidad = 'institucional';
	} elseif ($isGrupal) {
		$activeModalidad = 'grupal';
	} elseif ($isMixto) {
		$activeModalidad = 'individual';
	}
@endphp

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

				<!-- Título dinámico según modalidad -->
				<div class="text-center mb-4">
					@if($isInstitucional)
						<h4 class="font-weight-bold text-dark mb-1"><i class="fas fa-university text-primary mr-2"></i>Solicitud de Reserva Institucional</h4>
						<p class="text-muted small mb-0">Formulario formal para colegios, escuelas, facultades o contingentes oficiales.</p>
					@elseif($isGrupal)
						<h4 class="font-weight-bold text-dark mb-1"><i class="fas fa-users text-info mr-2"></i>Solicitud de Reserva Grupal</h4>
						<p class="text-muted small mb-0">Indique la cantidad de integrantes para la visita en grupo.</p>
					@elseif($isMixto)
						<h4 class="font-weight-bold text-dark mb-1"><i class="fas fa-layer-group text-primary mr-2"></i>Seleccione la Modalidad de Reserva</h4>
						<p class="text-muted small mb-0">Este servicio permite turnos Individuales, Grupales o Institucionales. Elija cómo desea registrarse.</p>
					@else
						<h4 class="font-weight-bold text-dark mb-1"><i class="fas fa-user-check text-primary mr-2"></i>Complete sus Datos Personales</h4>
						<p class="text-muted small mb-0">Revise la reserva y complete sus datos individuales para emitir su comprobante.</p>
					@endif
				</div>

				<!-- Resumen de la reserva elegida -->
				<div class="p-3 mb-4 rounded-lg border-0 shadow-xs" style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border-radius: 12px;">
					<div class="row align-items-center">
						<div class="col-md-6 col-12 mb-2 mb-md-0">
							<small class="text-uppercase text-primary font-weight-bold" style="letter-spacing: 0.5px;">Servicio Solicitado</small>
							<h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-university text-primary mr-1"></i> {{$dependencia['nombre']}}</h6>
						</div>
						<div class="col-md-6 col-12 text-md-right">
							<small class="text-uppercase text-success font-weight-bold" style="letter-spacing: 0.5px;">Fecha y Hora Asignada</small>
							<h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-calendar-check text-success mr-1"></i> {{$turno_fecha}} - {{$turno_hora}} hs</h6>
						</div>
					</div>
				</div>

				@if($isMixto)
					<!-- Selector de Modalidad para Trámites Mixtos -->
					<div class="p-3 mb-4 text-center rounded-lg bg-light border border-primary" style="border-radius: 14px; border-width: 2px;">
						<label class="font-weight-bold text-dark d-block mb-3" style="font-size: 1rem;"><i class="fas fa-sliders-h text-primary mr-1"></i> ¿Cómo desea solicitar este turno?</label>
						<div class="row justify-content-center" style="gap: 10px;">
							<div class="col-md-3 col-12 mb-2 mb-md-0">
								<button type="button" class="btn btn-primary w-100 font-weight-bold py-2 shadow-sm btn-modalidad-mixto" id="btn-mixto-individual" onclick="seleccionarModalidadMixto('individual')">
									<i class="fas fa-user d-block fa-lg mb-1"></i> 1. Individual
								</button>
							</div>
							<div class="col-md-3 col-12 mb-2 mb-md-0">
								<button type="button" class="btn btn-outline-info w-100 font-weight-bold py-2 shadow-sm btn-modalidad-mixto" id="btn-mixto-grupal" onclick="seleccionarModalidadMixto('grupal')">
									<i class="fas fa-users d-block fa-lg mb-1"></i> 2. Grupal
								</button>
							</div>
							<div class="col-md-4 col-12">
								<button type="button" class="btn btn-outline-success w-100 font-weight-bold py-2 shadow-sm btn-modalidad-mixto" id="btn-mixto-institucional" onclick="seleccionarModalidadMixto('institucional')">
									<i class="fas fa-university d-block fa-lg mb-1"></i> 3. Institucional / Escuela
								</button>
							</div>
						</div>
					</div>
				@endif

				{!! Form::open(['route' => 'tramite.guardar', 'method' => 'post', 'class' => 'form-horizontal', 'files' => true]) !!}
					
					<!-- =================================================== -->
					<!-- SECCIÓN FORMULARIO INSTITUCIONAL -->
					<!-- =================================================== -->
					<div id="sec-institucional" style="{{ ($activeModalidad === 'institucional') ? 'display: block;' : 'display: none;' }}">
						<input type="hidden" name="es_grupal" value="1">

						<div class="p-3 mb-4" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; border-left: 4px solid #2563eb !important;">
							<h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-school mr-2"></i>1. Datos de la Institución / Organismo</h6>
							<div class="row">
								<div class="col-md-6 col-12 mb-3">
									<label for="nombre_institucion" class="font-weight-bold text-secondary small mb-1">Nombre Oficial de la Institución / Escuela / Cátedra *</label>
									{!! Form::text('nombre_institucion', null, ['class' => 'form-control form-control-lg', 'placeholder' => 'Ej: Escuela N° 4012 / Facultad de Ciencias Exactas', 'required' => true, 'style' => 'font-size: 0.95rem; border-radius: 8px;']) !!}
								</div>
								<div class="col-md-6 col-12 mb-3">
									<label for="nivel_institucion" class="font-weight-bold text-secondary small mb-1">Nivel Educativo / Tipo de Establecimiento *</label>
									{!! Form::select('nivel_institucion', [
										'' => '--- Seleccionar Nivel ---',
										'Primario' => 'Nivel Primario',
										'Secundario' => 'Nivel Secundario',
										'Terciario' => 'Nivel Terciario / Instituto',
										'Universitario' => 'Nivel Universitario / Cátedra',
										'Centro Comunitario' => 'Centro Comunitario / ONG',
										'Otro' => 'Otro Organismo Público'
									], null, ['class' => 'form-control form-control-lg', 'required' => true, 'style' => 'font-size: 0.95rem; border-radius: 8px;']) !!}
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 col-12 mb-2">
									<label for="curso_comision" class="font-weight-bold text-secondary small mb-1">Año / Curso / Comisión / Cátedra (Opcional)</label>
									{!! Form::text('curso_comision', null, ['class' => 'form-control', 'placeholder' => 'Ej: 5to Año 2da Div / Cátedra de Astrofísica', 'style' => 'border-radius: 8px;']) !!}
								</div>
							</div>
						</div>

						<div class="p-3 mb-4" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; border-left: 4px solid #059669 !important;">
							<h6 class="font-weight-bold text-success mb-3"><i class="fas fa-user-tie mr-2"></i>2. Datos del Docente / Responsable a Cargo</h6>
							<div class="row">
								<div class="col-md-6 col-12 mb-3">
									<label for="nombre_apellido" class="font-weight-bold text-secondary small mb-1">Nombre y Apellido del Docente / Coordinador *</label>
									{!! Form::text('nombre_apellido', isset($usuario) ? $usuario->name : null, ['class' => 'form-control form-control-lg', 'placeholder' => 'Ej: Prof. Roberto Gómez', 'required' => true, 'style' => 'font-size: 0.95rem; border-radius: 8px;']) !!}
								</div>

								<div class="col-md-6 col-12 mb-3">
									<label for="cargo_responsable" class="font-weight-bold text-secondary small mb-1">Cargo / Función en la Institución *</label>
									{!! Form::text('cargo_responsable', null, ['class' => 'form-control form-control-lg', 'placeholder' => 'Ej: Director / Docente Titular / Coordinador', 'required' => true, 'style' => 'font-size: 0.95rem; border-radius: 8px;']) !!}
								</div>
							</div>

							<div class="row">
								<div class="col-md-4 col-12 mb-3">
									<label for="dni" class="font-weight-bold text-secondary small mb-1">DNI del Responsable *</label>
									{!! Form::text('dni', isset($usuario) && $usuario->persona ? $usuario->persona->nro_documento : null, ['class' => 'form-control', 'placeholder' => 'Ej: 30123456', 'required' => true, 'style' => 'border-radius: 8px;']) !!}
								</div>

								<div class="col-md-4 col-12 mb-3">
									<label for="celular" class="font-weight-bold text-secondary small mb-1">Teléfono Móvil / Celular Directo *</label>
									{!! Form::text('celular', isset($usuario) && $usuario->persona ? $usuario->persona->tel_movil : null, ['class' => 'form-control', 'placeholder' => 'Ej: 3874123456', 'required' => true, 'style' => 'border-radius: 8px;']) !!}
								</div>

								<div class="col-md-4 col-12 mb-3">
									<label for="email" class="font-weight-bold text-secondary small mb-1">Correo Electrónico Oficial *</label>
									{!! Form::email('email', isset($usuario) ? $usuario->email : null, ['class' => 'form-control', 'placeholder' => 'escuela@educacion.gob.ar', 'required' => true, 'style' => 'border-radius: 8px;']) !!}
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 col-12 mb-2">
									<label for="email_confirmation" class="font-weight-bold text-secondary small mb-1">Confirmar Correo Electrónico *</label>
									{!! Form::email('email_confirmation', isset($usuario) ? $usuario->email : null, ['class' => 'form-control', 'placeholder' => 'Reingrese su correo oficial', 'required' => true, 'style' => 'border-radius: 8px;']) !!}
								</div>
							</div>
						</div>

						<div class="p-3 mb-4" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; border-left: 4px solid #d97706 !important;">
							<h6 class="font-weight-bold text-warning mb-3" style="color: #d97706 !important;"><i class="fas fa-users mr-2"></i>3. Integrantes del Contingente</h6>
							
							<div class="row">
								<div class="col-md-6 col-12 mb-3">
									<label for="cantidad_personas" class="font-weight-bold text-secondary small mb-1">Cantidad Total de Estudiantes / Asistentes *</label>
									{!! Form::number('cantidad_personas', $minPersonas, [
										'class' => 'form-control form-control-lg', 
										'min' => $minPersonas, 
										'max' => $maxPersonas, 
										'required' => true,
										'style' => 'font-size: 1.1rem; font-weight: bold; border-radius: 8px;'
									]) !!}
									<small class="text-muted d-block mt-1">Límite configurado por la dependencia: de {{ $minPersonas }} a {{ $maxPersonas }} personas.</small>
								</div>

								<div class="col-md-6 col-12 mb-3">
									<label for="cantidad_acompanantes" class="font-weight-bold text-secondary small mb-1">Cantidad de Docentes / Acompañantes</label>
									{!! Form::number('cantidad_acompanantes', 1, [
										'class' => 'form-control form-control-lg', 
										'min' => 0, 
										'style' => 'font-size: 1.1rem; border-radius: 8px;'
									]) !!}
								</div>
							</div>

							<div class="form-group mb-0">
								<label for="archivo_integrantes" class="font-weight-bold text-secondary small mb-1">
									Adjuntar Nómina de Estudiantes (Excel / CSV) {{ $requiereNomina ? '*' : '(Opcional)' }}
								</label>
								@php
									$fileAttr = ['class' => 'form-control', 'accept' => '.xls,.xlsx,.csv', 'style' => 'border-radius: 8px; padding: 6px 12px;'];
									if ($requiereNomina) {
										$fileAttr['required'] = true;
									}
								@endphp
								{!! Form::file('archivo_integrantes', $fileAttr) !!}
								<small class="form-text text-muted mt-1">
									<i class="fas fa-download text-primary mr-1"></i> <a href="{{ asset('plantilla_integrantes.xlsx') }}" download class="font-weight-bold text-primary">Descargar plantilla Excel</a>. Si no la posee ahora, podrá cargarla más adelante desde la solapa de consulta de su reserva.
								</small>
							</div>
						</div>
					</div>

					<!-- =================================================== -->
					<!-- SECCIÓN FORMULARIO GRUPAL -->
					<!-- =================================================== -->
					<div id="sec-grupal" style="{{ ($activeModalidad === 'grupal') ? 'display: block;' : 'display: none;' }}">
						<input type="hidden" name="es_grupal" value="1">

						<div class="row">
							<div class="col-md-6 col-12 mb-3">
								<label for="nombre_apellido" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-user mr-1"></i> Nombre y Apellido del Responsable del Grupo *</label>
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
							<div class="col-md-6 col-12 mb-3">
								<label for="cantidad_personas" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-users mr-1"></i> Cantidad de Integrantes del Grupo *</label>
								{!! Form::number('cantidad_personas', $minPersonas, ['class' => 'form-control form-control-lg', 'min' => $minPersonas, 'max' => $maxPersonas, 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
								<small class="text-muted d-block mt-1">Límite configurado: de {{ $minPersonas }} a {{ $maxPersonas }} personas.</small>
							</div>
						</div>
					</div>

					<!-- =================================================== -->
					<!-- SECCIÓN FORMULARIO INDIVIDUAL -->
					<!-- =================================================== -->
					<div id="sec-individual" style="{{ ($activeModalidad === 'individual') ? 'display: block;' : 'display: none;' }}">
						<input type="hidden" name="cantidad_personas" value="1">
						<input type="hidden" name="es_grupal" value="0">

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
					</div>

					<hr class="my-4">

					<div class="d-flex justify-content-between align-items-center">
						<button onclick="location.href='{!! route('tramite.paso2') !!}'" class="btn btn-outline-secondary font-weight-bold" type="button" style="border-radius: 10px; padding: 8px 20px;">
							<i class="fas fa-arrow-left mr-1"></i> Volver a Horarios
						</button>
						<button type="submit" class="btn btn-gradient-primary btn-lg shadow">
							<span id="btn-submit-texto"><i class="fas fa-check-circle mr-1"></i> {{ $isInstitucional ? 'Enviar Solicitud Institucional' : 'Confirmar y Reservar Turno' }}</span>
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
	function seleccionarModalidadMixto(tipo) {
		$('.btn-modalidad-mixto').css({
			'background-color': '#ffffff',
			'color': '#1e293b',
			'border': '2px solid #cbd5e1',
			'font-weight': '700'
		});

		if (tipo === 'individual') {
			$('#btn-mixto-individual').css({
				'background-color': '#2563eb',
				'color': '#ffffff',
				'border': '2px solid #1d4ed8'
			});
			$('#sec-individual').show().find('input, select, textarea').prop('disabled', false);
			$('#sec-grupal, #sec-institucional').hide().find('input, select, textarea').prop('disabled', true);
			$('#btn-submit-texto').html('<i class="fas fa-check-circle mr-1"></i> Confirmar y Reservar Turno Individual');
		} else if (tipo === 'grupal') {
			$('#btn-mixto-grupal').css({
				'background-color': '#0284c7',
				'color': '#ffffff',
				'border': '2px solid #0369a1'
			});
			$('#sec-grupal').show().find('input, select, textarea').prop('disabled', false);
			$('#sec-individual, #sec-institucional').hide().find('input, select, textarea').prop('disabled', true);
			$('#btn-submit-texto').html('<i class="fas fa-check-circle mr-1"></i> Confirmar Reserva Grupal');
		} else if (tipo === 'institucional') {
			$('#btn-mixto-institucional').css({
				'background-color': '#16a34a',
				'color': '#ffffff',
				'border': '2px solid #15803d'
			});
			$('#sec-institucional').show().find('input, select, textarea').prop('disabled', false);
			$('#sec-individual, #sec-grupal').hide().find('input, select, textarea').prop('disabled', true);
			$('#btn-submit-texto').html('<i class="fas fa-check-circle mr-1"></i> Enviar Solicitud Institucional');
		}
	}

	$(document).ready(function() {
		seleccionarModalidadMixto('{!! $activeModalidad !!}');
	});
</script>
@endsection