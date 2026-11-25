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

	$horaLimpia = isset($turno_hora_display)
		? $turno_hora_display
		: (strpos($turno_hora ?? '', '|') !== false ? substr(explode('|', $turno_hora)[0], 0, 5) : substr($turno_hora ?? '', 0, 5));

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
					<div class="wizard-step-item completed">
						<div class="wizard-step-circle"><i class="fas fa-check"></i></div>
						<span class="wizard-step-title">1. Actividad</span>
					</div>
					<div class="wizard-step-item completed">
						<div class="wizard-step-circle"><i class="fas fa-check"></i></div>
						<span class="wizard-step-title">2. Día y Hora</span>
					</div>
					<div class="wizard-step-item active">
						<div class="wizard-step-circle"><i class="fas fa-id-card"></i></div>
						<span class="wizard-step-title">3. Tus Datos</span>
					</div>
				</div>

				<!-- Título dinámico según modalidad -->
				<div class="text-center mb-3">
					@if($isInstitucional)
						<h4 class="font-weight-bold text-dark mb-1"><i class="fas fa-university text-primary mr-2"></i>Solicitud de Visita Institucional</h4>
						<p class="text-muted mb-0" style="font-size: 0.95rem;">Completá los datos de la escuela o institución y del docente responsable.</p>
					@elseif($isGrupal)
						<h4 class="font-weight-bold text-dark mb-1"><i class="fas fa-users text-info mr-2"></i>Completá los Datos de tu Grupo</h4>
						<p class="text-muted mb-0" style="font-size: 0.95rem;">Ingresá los datos de quien coordina el grupo y cuántas personas asistirán.</p>
					@elseif($isMixto)
						<h4 class="font-weight-bold text-dark mb-1"><i class="fas fa-layer-group text-primary mr-2"></i>¡Último paso! Completá tus Datos</h4>
						<p class="text-muted mb-0" style="font-size: 0.95rem;">Elegí cómo vas a asistir (solo/a, en grupo o con una escuela) y completá el formulario.</p>
					@else
						<h4 class="font-weight-bold text-dark mb-1"><i class="fas fa-user-check text-primary mr-2"></i>¡Último paso! Completá tus Datos</h4>
						<p class="text-muted mb-0" style="font-size: 0.95rem;">Revisá la fecha y completá tus datos para emitir tu comprobante al instante.</p>
					@endif
				</div>

				<!-- Resumen de la reserva elegida -->
				<div class="p-3 mb-4 rounded-lg border" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-color: #bae6fd !important; border-radius: 12px;">
					<div class="row align-items-center">
						<div class="col-md-7 col-12 mb-2 mb-md-0">
							<small class="text-uppercase text-primary font-weight-bold d-block" style="letter-spacing: 0.5px; font-size: 0.72rem;">Actividad Seleccionada</small>
							<h6 class="font-weight-bold text-dark mb-0">
								<i class="fas fa-university text-primary mr-1"></i> {{ $dependencia['nombre'] }}
								@if(isset($dependencia_tramite) && $dependencia_tramite)
									<span class="text-muted font-weight-normal"> &middot; </span>{{ $dependencia_tramite->nombre }}
								@endif
							</h6>
						</div>
						<div class="col-md-5 col-12 text-md-right">
							<small class="text-uppercase text-success font-weight-bold d-block" style="letter-spacing: 0.5px; font-size: 0.72rem;">Día y Horario Elegido</small>
							<h6 class="font-weight-bold text-dark mb-0">
								<i class="fas fa-calendar-check text-success mr-1"></i> {{ $turno_fecha }} a las {{ $horaLimpia }} hs
							</h6>
						</div>
					</div>
				</div>

				@if($isMixto)
					<!-- Selector de Modalidad para Trámites Mixtos -->
					<div class="p-3 mb-4 text-center rounded-lg bg-light border" style="border-radius: 14px; border-color: #cbd5e1 !important;">
						<label class="font-weight-bold text-dark d-block mb-3" style="font-size: 1rem;">
							<i class="fas fa-sliders-h text-primary mr-1"></i> Modalidad de reserva
						</label>
						<div class="row justify-content-center">
							<div class="col-md-4 col-12 mb-2 mb-md-0">
								<button type="button" class="btn btn-primary w-100 font-weight-bold py-3 shadow-sm btn-modalidad-mixto" id="btn-mixto-individual" onclick="seleccionarModalidadMixto('individual')" style="border-radius: 12px;">
									<i class="fas fa-user d-block fa-lg mb-1"></i>
									<span class="d-block">1. Individual</span>
									<small class="d-block font-weight-normal" style="opacity: 0.85; font-size: 0.78rem;">Voy solo/a (1 persona)</small>
								</button>
							</div>
							<div class="col-md-4 col-12 mb-2 mb-md-0">
								<button type="button" class="btn btn-outline-info w-100 font-weight-bold py-3 shadow-sm btn-modalidad-mixto" id="btn-mixto-grupal" onclick="seleccionarModalidadMixto('grupal')" style="border-radius: 12px;">
									<i class="fas fa-users d-block fa-lg mb-1"></i>
									<span class="d-block">2. Grupal / Familia</span>
									<small class="d-block font-weight-normal" style="opacity: 0.85; font-size: 0.78rem;">De {{ $minPersonas }} a {{ $maxPersonas }} personas</small>
								</button>
							</div>
							<div class="col-md-4 col-12">
								<button type="button" class="btn btn-outline-success w-100 font-weight-bold py-3 shadow-sm btn-modalidad-mixto" id="btn-mixto-institucional" onclick="seleccionarModalidadMixto('institucional')" style="border-radius: 12px;">
									<i class="fas fa-school d-block fa-lg mb-1"></i>
									<span class="d-block">3. Escuela / Institución</span>
									<small class="d-block font-weight-normal" style="opacity: 0.85; font-size: 0.78rem;">Colegios, cursos o cátedras</small>
								</button>
							</div>
						</div>
					</div>
				@endif

				{!! Form::open(['route' => 'tramite.guardar', 'method' => 'post', 'class' => 'form-horizontal', 'files' => true, 'id' => 'form-paso3']) !!}
					
					<!-- =================================================== -->
					<!-- SECCIÓN FORMULARIO INSTITUCIONAL -->
					<!-- =================================================== -->
					<div id="sec-institucional" style="{{ ($activeModalidad === 'institucional') ? 'display: block;' : 'display: none;' }}">
						<input type="hidden" name="es_grupal" value="1">

						<div class="p-3 mb-4" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; border-left: 4px solid #2563eb !important;">
							<h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-school mr-2"></i>1. Datos de la Institución o Escuela</h6>
							<div class="row">
								<div class="col-md-6 col-12 mb-3">
									<label for="inst_nombre_institucion" class="font-weight-bold text-secondary small mb-1">
										Nombre de la Escuela / Institución / Cátedra *
									</label>
									{!! Form::text('nombre_institucion', null, ['id' => 'inst_nombre_institucion', 'class' => 'form-control form-control-lg', 'placeholder' => 'Ej: Escuela N° 4012 / Colegio Nacional', 'required' => true, 'style' => 'font-size: 0.95rem; border-radius: 8px;']) !!}
								</div>
								<div class="col-md-6 col-12 mb-3">
									<label for="inst_nivel_institucion" class="font-weight-bold text-secondary small mb-1">Nivel Educativo / Tipo *</label>
									{!! Form::select('nivel_institucion', [
										'' => '--- Elegí una opción ---',
										'Inicial' => 'Nivel Inicial / Jardín',
										'Primario' => 'Nivel Primario',
										'Secundario' => 'Nivel Secundario',
										'Terciario' => 'Nivel Terciario / Instituto',
										'Universitario' => 'Nivel Universitario / Cátedra',
										'Centro Comunitario' => 'Centro Comunitario / ONG',
										'Otro' => 'Otro Organismo Público'
									], null, ['id' => 'inst_nivel_institucion', 'class' => 'form-control form-control-lg', 'required' => true, 'style' => 'font-size: 0.95rem; border-radius: 8px;']) !!}
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 col-12 mb-2">
									<label for="inst_curso_comision" class="font-weight-bold text-secondary small mb-1">Año, Curso o Comisión (Opcional)</label>
									{!! Form::text('curso_comision', null, ['id' => 'inst_curso_comision', 'class' => 'form-control', 'placeholder' => 'Ej: 5to Año 2da Div / Cátedra de Física', 'style' => 'border-radius: 8px;']) !!}
								</div>
							</div>
						</div>

						<div class="p-3 mb-4" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; border-left: 4px solid #059669 !important;">
							<h6 class="font-weight-bold text-success mb-3"><i class="fas fa-user-tie mr-2"></i>2. Datos del Docente o Responsable a Cargo</h6>
							<div class="row">
								<div class="col-md-6 col-12 mb-3">
									<label for="inst_nombre_apellido" class="font-weight-bold text-secondary small mb-1">Nombre y Apellido del Responsable *</label>
									{!! Form::text('nombre_apellido', isset($usuario) ? $usuario->name : null, ['id' => 'inst_nombre_apellido', 'class' => 'form-control form-control-lg', 'placeholder' => 'Ej: Roberto Gómez', 'required' => true, 'autocomplete' => 'name', 'style' => 'font-size: 0.95rem; border-radius: 8px;']) !!}
								</div>

								<div class="col-md-6 col-12 mb-3">
									<label for="inst_cargo_responsable" class="font-weight-bold text-secondary small mb-1">Cargo o Función *</label>
									{!! Form::text('cargo_responsable', null, ['id' => 'inst_cargo_responsable', 'class' => 'form-control form-control-lg', 'placeholder' => 'Ej: Docente / Director / Coordinador', 'required' => true, 'style' => 'font-size: 0.95rem; border-radius: 8px;']) !!}
								</div>
							</div>

							<div class="row">
								<div class="col-md-4 col-12 mb-3">
									<label for="inst_dni" class="font-weight-bold text-secondary small mb-1">DNI del Responsable *</label>
									{!! Form::text('dni', isset($usuario) && $usuario->persona ? $usuario->persona->nro_documento : null, ['id' => 'inst_dni', 'class' => 'form-control', 'placeholder' => 'Ej: 30123456', 'inputmode' => 'numeric', 'required' => true, 'style' => 'border-radius: 8px;']) !!}
									<small class="text-muted">Sin puntos ni espacios.</small>
								</div>

								<div class="col-md-4 col-12 mb-3">
									<label for="inst_celular" class="font-weight-bold text-secondary small mb-1">Celular de Contacto *</label>
									{!! Form::text('celular', isset($usuario) && $usuario->persona ? $usuario->persona->tel_movil : null, ['id' => 'inst_celular', 'class' => 'form-control', 'placeholder' => 'Ej: 3874123456', 'inputmode' => 'tel', 'required' => true, 'style' => 'border-radius: 8px;']) !!}
									<small class="text-muted">Con característica, sin 0 ni 15.</small>
								</div>

								<div class="col-md-4 col-12 mb-3">
									<label for="inst_email" class="font-weight-bold text-secondary small mb-1">Correo Electrónico *</label>
									{!! Form::email('email', isset($usuario) ? $usuario->email : null, ['id' => 'inst_email', 'class' => 'form-control js-email-primary', 'placeholder' => 'docente@escuela.edu.ar', 'required' => true, 'style' => 'border-radius: 8px;']) !!}
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 col-12 mb-2">
									<label for="inst_email_confirmation" class="font-weight-bold text-secondary small mb-1">Repetir Correo Electrónico *</label>
									{!! Form::email('email_confirmation', isset($usuario) ? $usuario->email : null, ['id' => 'inst_email_confirmation', 'class' => 'form-control js-email-confirm', 'placeholder' => 'Volvé a escribir el mismo correo', 'required' => true, 'style' => 'border-radius: 8px;']) !!}
									<small class="js-email-feedback d-block mt-1 font-weight-bold"></small>
								</div>
							</div>
						</div>

						<div class="p-3 mb-4" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; border-left: 4px solid #d97706 !important;">
							<h6 class="font-weight-bold mb-3" style="color: #b45309;"><i class="fas fa-users mr-2"></i>3. Cantidad de Asistentes y Nómina</h6>
							
							<div class="row">
								<div class="col-md-6 col-12 mb-3">
									<label for="inst_cantidad_personas" class="font-weight-bold text-secondary small mb-1">Cantidad de Estudiantes / Asistentes *</label>
									{!! Form::number('cantidad_personas', $minPersonas, [
										'id' => 'inst_cantidad_personas',
										'class' => 'form-control form-control-lg', 
										'min' => $minPersonas, 
										'max' => $maxPersonas, 
										'required' => true,
										'style' => 'font-size: 1.1rem; font-weight: bold; border-radius: 8px;'
									]) !!}
									<small class="text-muted d-block mt-1">Permitido para esta actividad: de <strong>{{ $minPersonas }}</strong> a <strong>{{ $maxPersonas }}</strong> personas.</small>
								</div>

								<div class="col-md-6 col-12 mb-3">
									<label for="inst_cantidad_acompanantes" class="font-weight-bold text-secondary small mb-1">Cantidad de Docentes / Acompañantes</label>
									{!! Form::number('cantidad_acompanantes', 1, [
										'id' => 'inst_cantidad_acompanantes',
										'class' => 'form-control form-control-lg', 
										'min' => 0, 
										'style' => 'font-size: 1.1rem; border-radius: 8px;'
									]) !!}
								</div>
							</div>

							<div class="form-group mb-0">
								<label for="inst_archivo_integrantes" class="font-weight-bold text-secondary small mb-1">
									Lista de Estudiantes en Excel / CSV {{ $requiereNomina ? '*' : '(Opcional — podés cargarla después)' }}
									<button type="button" class="help-tip ml-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Si aún no tenés el archivo Excel, después de confirmar tu turno podrás cargar los nombres fácilmente uno por uno desde la pantalla.">?</button>
								</label>
								@php
									$fileAttr = ['id' => 'inst_archivo_integrantes', 'class' => 'form-control', 'accept' => '.xls,.xlsx,.csv', 'style' => 'border-radius: 8px; padding: 6px 12px;'];
									if ($requiereNomina) {
										$fileAttr['required'] = true;
									}
								@endphp
								{!! Form::file('archivo_integrantes', $fileAttr) !!}
								<small class="form-text text-muted mt-1">
									<i class="fas fa-download text-primary mr-1"></i> <a href="{{ asset('plantilla_integrantes.xlsx') }}" download class="font-weight-bold text-primary">Descargar plantilla Excel de ejemplo</a>.
									@if(!$requiereNomina)
										Si no la tenés ahora, no te preocupes: podrás cargarla más tarde en línea con tu código de reserva.
									@endif
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
								<label for="grup_nombre_apellido" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-user mr-1"></i> Nombre y Apellido del Responsable *</label>
								{!! Form::text('nombre_apellido', isset($usuario) ? $usuario->name : null, ['id' => 'grup_nombre_apellido', 'class' => 'form-control form-control-lg', 'placeholder' => 'Ej: María González', 'autocomplete' => 'name', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
							</div>

							<div class="col-md-6 col-12 mb-3">
								<label for="grup_dni" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-id-card mr-1"></i> Número de DNI *</label>
								{!! Form::text('dni', isset($usuario) && $usuario->persona ? $usuario->persona->nro_documento : null, ['id' => 'grup_dni', 'class' => 'form-control form-control-lg', 'placeholder' => 'Ej: 38123456', 'inputmode' => 'numeric', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
								<small class="text-muted">Solo números, sin puntos ni espacios.</small>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 col-12 mb-3">
								<label for="grup_celular" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-phone-alt mr-1"></i> Teléfono Celular *</label>
								{!! Form::text('celular', isset($usuario) && $usuario->persona ? $usuario->persona->tel_movil : null, ['id' => 'grup_celular', 'class' => 'form-control form-control-lg', 'placeholder' => 'Ej: 3874123456', 'inputmode' => 'tel', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
								<small class="text-muted">Con código de área, sin 0 ni 15.</small>
							</div>

							<div class="col-md-6 col-12 mb-3">
								<label for="grup_cantidad_personas" class="font-weight-bold text-secondary small mb-1">
									<i class="fas fa-users mr-1"></i> Cantidad Total de Personas *
									<button type="button" class="help-tip ml-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Incluite a vos en el total de asistentes del grupo.">?</button>
								</label>
								{!! Form::number('cantidad_personas', $minPersonas, ['id' => 'grup_cantidad_personas', 'class' => 'form-control form-control-lg', 'min' => $minPersonas, 'max' => $maxPersonas, 'required' => true, 'style' => 'font-size: 1rem; font-weight: bold; border-radius: 10px;']) !!}
								<small class="text-muted d-block mt-1">Permitido para esta actividad: de <strong>{{ $minPersonas }}</strong> a <strong>{{ $maxPersonas }}</strong> personas.</small>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 col-12 mb-3">
								<label for="grup_email" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-envelope mr-1"></i> Correo Electrónico *</label>
								{!! Form::email('email', isset($usuario) ? $usuario->email : null, ['id' => 'grup_email', 'class' => 'form-control form-control-lg js-email-primary', 'placeholder' => 'Ej: tucorreo@gmail.com', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
							</div>

							<div class="col-md-6 col-12 mb-3">
								<label for="grup_email_confirmation" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-check-double mr-1"></i> Repetí tu Correo Electrónico *</label>
								{!! Form::email('email_confirmation', isset($usuario) ? $usuario->email : null, ['id' => 'grup_email_confirmation', 'class' => 'form-control form-control-lg js-email-confirm', 'placeholder' => 'Volvé a escribir el mismo correo', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
								<small class="js-email-feedback d-block mt-1 font-weight-bold"></small>
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
								<label for="ind_nombre_apellido" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-user mr-1"></i> Nombre y Apellido Completo *</label>
								{!! Form::text('nombre_apellido', isset($usuario) ? $usuario->name : null, ['id' => 'ind_nombre_apellido', 'class' => 'form-control form-control-lg', 'placeholder' => 'Ej: María González', 'autocomplete' => 'name', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
							</div>

							<div class="col-md-6 col-12 mb-3">
								<label for="ind_dni" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-id-card mr-1"></i> Número de DNI *</label>
								{!! Form::text('dni', isset($usuario) && $usuario->persona ? $usuario->persona->nro_documento : null, ['id' => 'ind_dni', 'class' => 'form-control form-control-lg', 'placeholder' => 'Ej: 38123456', 'inputmode' => 'numeric', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
								<small class="text-muted">Solo números, sin puntos ni espacios.</small>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 col-12 mb-3">
								<label for="ind_celular" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-phone-alt mr-1"></i> Teléfono Celular *</label>
								{!! Form::text('celular', isset($usuario) && $usuario->persona ? $usuario->persona->tel_movil : null, ['id' => 'ind_celular', 'class' => 'form-control form-control-lg', 'placeholder' => 'Ej: 3874123456', 'inputmode' => 'tel', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
								<small class="text-muted">Con código de área, sin 0 ni 15.</small>
							</div>

							<div class="col-md-6 col-12 mb-3">
								<label for="ind_email" class="font-weight-bold text-secondary small mb-1">
									<i class="fas fa-envelope mr-1"></i> Correo Electrónico *
									<button type="button" class="help-tip ml-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Enviaremos el comprobante y el código de reserva a esta casilla.">?</button>
								</label>
								{!! Form::email('email', isset($usuario) ? $usuario->email : null, ['id' => 'ind_email', 'class' => 'form-control form-control-lg js-email-primary', 'placeholder' => 'Ej: tucorreo@gmail.com', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 col-12 mb-3">
								<label for="ind_email_confirmation" class="font-weight-bold text-secondary small mb-1"><i class="fas fa-check-double mr-1"></i> Repetí tu Correo Electrónico *</label>
								{!! Form::email('email_confirmation', isset($usuario) ? $usuario->email : null, ['id' => 'ind_email_confirmation', 'class' => 'form-control form-control-lg js-email-confirm', 'placeholder' => 'Volvé a escribir el mismo correo', 'required' => true, 'style' => 'font-size: 1rem; border-radius: 10px;']) !!}
								<small class="js-email-feedback d-block mt-1 font-weight-bold"></small>
							</div>
						</div>
					</div>

					<hr class="my-4">

					<div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 12px;">
						<a href="{!! route('tramite.paso2') !!}" class="btn btn-outline-secondary font-weight-bold" style="border-radius: 10px; padding: 10px 20px;">
							<i class="fas fa-arrow-left mr-1"></i> Cambiar Día u Horario
						</a>
						<button type="submit" id="btn-submit-paso3" class="btn btn-gradient-primary btn-lg shadow">
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
			$('#btn-submit-texto').html('<i class="fas fa-check-circle mr-1"></i> Confirmar Reserva Individual');
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

	function validarCoincidenciaEmail($scope) {
		var email1 = $.trim($scope.find('.js-email-primary').val() || '');
		var email2 = $.trim($scope.find('.js-email-confirm').val() || '');
		var $fb = $scope.find('.js-email-feedback');

		if (!email2) {
			$fb.text('').removeClass('text-success text-danger');
			return;
		}
		if (email1.toLowerCase() === email2.toLowerCase()) {
			$fb.html('<i class="fas fa-check-circle mr-1"></i> Los correos coinciden').removeClass('text-danger').addClass('text-success');
		} else {
			$fb.html('<i class="fas fa-exclamation-triangle mr-1"></i> Todavía no coinciden, revisá que estén escritos igual').removeClass('text-success').addClass('text-danger');
		}
	}

	$(document).ready(function() {
		seleccionarModalidadMixto('{!! $activeModalidad !!}');

		$('#sec-individual, #sec-grupal, #sec-institucional').each(function() {
			var $sec = $(this);
			$sec.find('.js-email-primary, .js-email-confirm').on('input blur', function() {
				validarCoincidenciaEmail($sec);
			});
		});

		$('#form-paso3').on('submit', function(e) {
			var $activeSec = $('#sec-individual:visible, #sec-grupal:visible, #sec-institucional:visible').first();
			if ($activeSec.length) {
				var e1 = $.trim($activeSec.find('.js-email-primary').val() || '');
				var e2 = $.trim($activeSec.find('.js-email-confirm').val() || '');
				if (e1 && e2 && e1.toLowerCase() !== e2.toLowerCase()) {
					e.preventDefault();
					validarCoincidenciaEmail($activeSec);
					$activeSec.find('.js-email-confirm').focus();
					return false;
				}
			}
			$('#btn-submit-paso3').prop('disabled', true);
			$('#btn-submit-texto').html('<i class="fas fa-spinner fa-spin mr-2"></i> Registrando tu reserva...');
		});
	});
</script>
@endsection