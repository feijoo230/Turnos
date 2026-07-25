<div class="row justify-content-center my-4">
	<div class="col-lg-8 col-xl-7">
		<div class="card border-0 shadow-sm" style="border-radius: 16px; background: white;">
			<div class="card-body p-4 text-center">
				<h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-search text-primary mr-2"></i>Consultar Estado de Turno Reservado</h5>
				<p class="text-muted small mb-3">Ingrese el código de reserva y su número de DNI para consultar los detalles o descargar su comprobante.</p>

				{!! Form::open(['route' => 'turno.buscar', 'method'=> 'get', 'class' => 'form-horizontal']) !!}
					<div class="row">
						<div class="col-md-6 col-12 mb-2">
							{!! Form::text('codigo_turno', null, ['class' => 'form-control form-control-lg', 'placeholder' => 'Código de turno (Ej: TUR-123)', 'style' => 'border-radius: 10px; font-size: 0.95rem;']) !!}
						</div>
						<div class="col-md-6 col-12 mb-2">
							{!! Form::text('dni_turno', null, ['class' => 'form-control form-control-lg', 'placeholder' => 'Número de DNI', 'style' => 'border-radius: 10px; font-size: 0.95rem;']) !!}
						</div>
					</div>
					<div class="text-right mt-2">
						<button type="submit" class="btn btn-primary font-weight-bold px-4" style="border-radius: 10px;">
							<i class="fas fa-search mr-1"></i> Consultar
						</button>
					</div>
				{!! Form::close() !!}

				@if (session('turno_reserva_busqueda'))
					@php($turno_reserva_busqueda = session('turno_reserva_busqueda'))
					<div id="alert-consulta" class="mt-4 text-left text-start">
						<div class="card border-primary shadow-xs" style="border-radius: 12px; border-width: 2px;">
							<div class="card-header bg-primary text-white font-weight-bold d-flex justify-content-between align-items-center" style="border-radius: 10px 10px 0 0;">
								<span><i class="fas fa-ticket-alt mr-1"></i> RESERVA ENCONTRADA: {!! $turno_reserva_busqueda->codigo !!}</span>
								<a href="{!! route('tramite.index') !!}" class="text-white opacity-75" style="text-decoration:none;">&times;</a>
							</div>
							<div class="card-body p-3 bg-light">
								@if ($turno_reserva_busqueda->turno_tramite && $turno_reserva_busqueda->turno_tramite->tramite && $turno_reserva_busqueda->turno_tramite->tramite->dependencia)
									<p class="mb-1"><strong><i class="fas fa-university text-primary mr-1"></i> Dirección:</strong> {!! $turno_reserva_busqueda->turno_tramite->tramite->dependencia->nombre !!}</p>
								@endif
								@if ($turno_reserva_busqueda->turno_tramite && $turno_reserva_busqueda->turno_tramite->tramite)
									<p class="mb-1"><strong><i class="fas fa-list-alt text-success mr-1"></i> Trámite:</strong> {!! $turno_reserva_busqueda->turno_tramite->tramite->nombre !!}</p>
								@endif
								<p class="mb-1"><strong><i class="fas fa-calendar-check text-info mr-1"></i> Fecha y Hora:</strong> {!! $turno_reserva_busqueda->fecha_hora->format('d/m/Y H:i') !!} hs</p>
								<p class="mb-1"><strong><i class="fas fa-user text-secondary mr-1"></i> Titular / Responsable:</strong> {!! $turno_reserva_busqueda->nombre_apellido !!}</p>
								<p class="mb-1"><strong><i class="fas fa-id-card text-secondary mr-1"></i> DNI:</strong> {!! $turno_reserva_busqueda->dni !!}</p>
								<p class="mb-2"><strong><i class="fas fa-envelope text-secondary mr-1"></i> Email:</strong> {!! $turno_reserva_busqueda->email !!}</p>

								<div class="mb-3">
									<strong><i class="fas fa-info-circle text-primary mr-1"></i> Tipo de Reserva:</strong>
									@if($turno_reserva_busqueda->nombre_institucion)
										<span class="badge px-3 py-1 text-white font-weight-bold" style="background-color: #2563eb; color: #ffffff !important; font-size: 0.85rem;"><i class="fas fa-university mr-1"></i> Institucional ({!! $turno_reserva_busqueda->nombre_institucion !!})</span>
									@elseif($turno_reserva_busqueda->es_grupal)
										<span class="badge px-3 py-1 text-white font-weight-bold" style="background-color: #0284c7; color: #ffffff !important; font-size: 0.85rem;"><i class="fas fa-users mr-1"></i> Grupal ({!! $turno_reserva_busqueda->cantidad_personas !!} personas)</span>
									@else
										<span class="badge px-3 py-1 text-white font-weight-bold" style="background-color: #475569; color: #ffffff !important; font-size: 0.85rem;"><i class="fas fa-user mr-1"></i> Individual</span>
									@endif
								</div>

								@if($turno_reserva_busqueda->es_grupal || $turno_reserva_busqueda->nombre_institucion)
									<div class="alert alert-info mb-3 border-0 shadow-xs" style="border-radius: 10px; background: #e0f2fe; color: #0369a1;">
										<div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
											<div>
												<strong class="d-block"><i class="fas fa-file-excel mr-1"></i> Nómina de Asistentes ({!! $turno_reserva_busqueda->integrantes ? $turno_reserva_busqueda->integrantes->count() : 0 !!} cargados de {!! $turno_reserva_busqueda->cantidad_personas !!})</strong>
												<small class="d-block">Puede cargar o actualizar la nómina en línea o con Excel sin estar logueado.</small>
											</div>
											<a href="{!! route('tramite.integrantes.form', [$turno_reserva_busqueda->codigo]) !!}" class="btn btn-info btn-sm font-weight-bold shadow-sm" style="border-radius: 8px;">
												<i class="fas fa-user-edit mr-1"></i> Cargar / Editar Integrantes
											</a>
										</div>
									</div>
								@endif
								
								<div class="text-center pt-2 border-top">
									<a href="{!! route('turnos.print', [$turno_reserva_busqueda->id]) !!}" class="btn btn-danger font-weight-bold" style="border-radius: 8px;">
										<i class="fas fa-file-pdf mr-1"></i> Descargar Comprobante PDF
									</a>
								</div>
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>