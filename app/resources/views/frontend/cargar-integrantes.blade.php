@extends('layouts.frontend')
@section('content')

<div class="row justify-content-center">
	<div class="col-lg-10 col-xl-9">
		<div class="card box-turno">
			<div class="card-body p-4">
				<div class="text-center mb-4">
					<div class="rounded-circle bg-light-info text-info mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: #e0f2fe;">
						<i class="fas fa-users-cog fa-2x text-info"></i>
					</div>
					<h3 class="font-weight-bold text-dark mb-1">Nómina de Integrantes / Asistentes</h3>
					<p class="text-muted small mb-0">Gestión de la lista de asistentes para la reserva <strong>{!! $reserva->codigo !!}</strong></p>
				</div>



				<!-- Datos Principales de la Reserva -->
				<div class="p-3 mb-4 rounded-lg bg-light border border-light" style="border-radius: 12px;">
					<div class="row align-items-center">
						<div class="col-md-6 col-12 mb-2 mb-md-0">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.75rem;">Titular / Institución</small>
							<span class="font-weight-bold text-dark">{!! $reserva->nombre_apellido !!} {!! $reserva->nombre_institucion ? '('.$reserva->nombre_institucion.')' : '' !!}</span>
						</div>
						<div class="col-md-6 col-12 text-md-right">
							<small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.75rem;">Fecha y Hora Asignada</small>
							<span class="font-weight-bold text-success"><i class="fas fa-calendar-alt mr-1"></i> {!! $reserva->fecha_hora->format('d/m/Y H:i') !!} hs</span>
						</div>
					</div>
				</div>

				<!-- Nav Tabs Estilo Odoo (Excel vs Carga Manual Interactiva) -->
				<ul class="nav nav-pills nav-fill mb-4 p-1 bg-light rounded" id="nominaTabs" role="tablist" style="border-radius: 10px; border: 1px solid #e2e8f0;">
					<li class="nav-item" role="presentation">
						<button class="nav-link active font-weight-bold py-2" id="tab-manual" data-bs-toggle="tab" data-bs-target="#content-manual" type="button" role="tab" aria-controls="content-manual" aria-selected="true" style="border-radius: 8px;">
							<i class="fas fa-edit mr-1"></i> Carga Directa en Pantalla (Formulario)
						</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link font-weight-bold py-2" id="tab-excel" data-bs-toggle="tab" data-bs-target="#content-excel" type="button" role="tab" aria-controls="content-excel" aria-selected="false" style="border-radius: 8px;">
							<i class="fas fa-file-excel mr-1"></i> Importar Archivo Excel / CSV
						</button>
					</li>
				</ul>

				<div class="tab-content" id="nominaTabsContent">
					<!-- TAB 1: CARGA MANUAL INTERACTIVA ESTILO ODOO -->
					<div class="tab-pane fade show active" id="content-manual" role="tabpanel" aria-labelledby="tab-manual">
						{!! Form::open(['route' => ['tramite.integrantes.guardar', $reserva->codigo], 'method' => 'post', 'id' => 'form-integrantes-manuales']) !!}
							<div class="p-4 bg-light rounded-lg border border-light" style="border-radius: 12px;">
								<div class="d-flex justify-content-between align-items-center mb-3">
									<h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-list-alt text-primary mr-2"></i>Nómina Interactiva de Integrantes</h5>
									<small class="text-muted">Total Declarado: <strong>{{ $reserva->cantidad_personas }} asistentes</strong></small>
								</div>

								<div class="table-responsive">
									<table class="table table-bordered align-middle bg-white" id="tabla-integrantes-manuales" style="border-radius: 8px; overflow: hidden;">
										<thead class="bg-primary text-white text-center">
											<tr>
												<th style="width: 50px;">#</th>
												<th>Nombre *</th>
												<th>Apellido *</th>
												<th>DNI / Documento *</th>
												<th style="width: 50px;">Acción</th>
											</tr>
										</thead>
										<tbody>
											@if($reserva->integrantes->count() > 0)
												@foreach($reserva->integrantes as $index => $integrante)
													<tr class="integrante-row text-center">
														<td class="align-middle"><strong>{{ $index + 1 }}</strong></td>
														<td>
															<input type="text" name="integrantes_manuales[{{$index}}][nombre]" value="{{ $integrante->nombre }}" class="form-control form-control-sm text-center" placeholder="Nombre" required style="border-radius: 6px;">
														</td>
														<td>
															<input type="text" name="integrantes_manuales[{{$index}}][apellido]" value="{{ $integrante->apellido }}" class="form-control form-control-sm text-center" placeholder="Apellido" required style="border-radius: 6px;">
														</td>
														<td>
															<input type="text" name="integrantes_manuales[{{$index}}][dni]" value="{{ $integrante->dni }}" class="form-control form-control-sm text-center" placeholder="DNI" required style="border-radius: 6px;">
														</td>
														<td class="align-middle">
															<button type="button" class="btn btn-danger btn-sm remove-integrante-row" title="Quitar Integrante"><i class="fas fa-trash-alt"></i></button>
														</td>
													</tr>
												@endforeach
											@else
												<tr class="integrante-row text-center">
													<td class="align-middle"><strong>1</strong></td>
													<td>
														<input type="text" name="integrantes_manuales[0][nombre]" class="form-control form-control-sm text-center" placeholder="Ej: Juan" required style="border-radius: 6px;">
													</td>
													<td>
														<input type="text" name="integrantes_manuales[0][apellido]" class="form-control form-control-sm text-center" placeholder="Ej: Pérez" required style="border-radius: 6px;">
													</td>
													<td>
														<input type="text" name="integrantes_manuales[0][dni]" class="form-control form-control-sm text-center" placeholder="Ej: 40123456" required style="border-radius: 6px;">
													</td>
													<td class="align-middle">
														<button type="button" class="btn btn-danger btn-sm remove-integrante-row" title="Quitar Integrante"><i class="fas fa-trash-alt"></i></button>
													</td>
												</tr>
											@endif
										</tbody>
									</table>
								</div>

								<div class="d-flex justify-content-between align-items-center mt-3">
									<button type="button" class="btn btn-outline-success font-weight-bold" id="add-integrante-row" style="border-radius: 8px;">
										<i class="fas fa-plus-circle mr-1"></i> Agregar Integrante
									</button>

									<button type="submit" class="btn btn-primary font-weight-bold px-4" style="border-radius: 8px;">
										<i class="fas fa-save mr-1"></i> Guardar Nómina de Integrantes
									</button>
								</div>
							</div>
						{!! Form::close() !!}
					</div>

					<!-- TAB 2: IMPORTACIÓN POR EXCEL / CSV -->
					<div class="tab-pane fade" id="content-excel" role="tabpanel" aria-labelledby="tab-excel">
						{!! Form::open(['route' => ['tramite.integrantes.guardar', $reserva->codigo], 'method' => 'post', 'files' => true]) !!}
							<div class="p-4 bg-light rounded-lg border border-light" style="border-radius: 12px;">
								<h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-file-upload text-success mr-2"></i>Cargar o Reemplazar mediante Archivo Excel / CSV</h5>
								
								<div class="form-group mb-3">
									<label for="archivo_integrantes" class="small font-weight-bold">Seleccionar archivo Excel (.xlsx, .xls) con las columnas: nombre, apellido, dni *</label>
									{!! Form::file('archivo_integrantes', ['class' => 'form-control', 'accept' => '.xls,.xlsx,.csv', 'required' => true, 'style' => 'border-radius: 8px; padding: 6px 12px;']) !!}
									<small class="form-text text-muted mt-2">
										<i class="fas fa-download text-primary mr-1"></i> <a href="{{ asset('plantilla_integrantes.xlsx') }}" download class="font-weight-bold text-primary">Descargar plantilla Excel de ejemplo</a>.
									</small>
								</div>

								<button type="submit" class="btn btn-success font-weight-bold px-4" style="border-radius: 8px;">
									<i class="fas fa-upload mr-1"></i> Importar Excel de Integrantes
								</button>
							</div>
						{!! Form::close() !!}
					</div>
				</div>

				<div class="mt-4 text-center">
					<a href="{!! route('turnos.print', [$reserva->id]) !!}" class="btn btn-outline-danger font-weight-bold mr-2" target="_blank" style="border-radius: 8px;">
						<i class="fas fa-file-pdf mr-1"></i> Ver / Imprimir Comprobante PDF
					</a>
					<a href="{!! route('turnos') !!}" class="btn btn-secondary font-weight-bold" style="border-radius: 8px;">
						<i class="fas fa-home mr-1"></i> Volver al Inicio
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

@stop

@section('script')
<script>
	document.addEventListener('DOMContentLoaded', function() {
		let rowCount = document.querySelectorAll('.integrante-row').length;

		document.getElementById('add-integrante-row').addEventListener('click', function() {
			rowCount++;
			const tableBody = document.querySelector('#tabla-integrantes-manuales tbody');
			const newRow = document.createElement('tr');
			newRow.className = 'integrante-row text-center';

			newRow.innerHTML = `
				<td class="align-middle"><strong>${tableBody.children.length + 1}</strong></td>
				<td>
					<input type="text" name="integrantes_manuales[${rowCount}][nombre]" class="form-control form-control-sm text-center" placeholder="Nombre" required style="border-radius: 6px;">
				</td>
				<td>
					<input type="text" name="integrantes_manuales[${rowCount}][apellido]" class="form-control form-control-sm text-center" placeholder="Apellido" required style="border-radius: 6px;">
				</td>
				<td>
					<input type="text" name="integrantes_manuales[${rowCount}][dni]" class="form-control form-control-sm text-center" placeholder="DNI" required style="border-radius: 6px;">
				</td>
				<td class="align-middle">
					<button type="button" class="btn btn-danger btn-sm remove-integrante-row" title="Quitar Integrante"><i class="fas fa-trash-alt"></i></button>
				</td>
			`;

			tableBody.appendChild(newRow);
			attachRemoveEvent(newRow.querySelector('.remove-integrante-row'));
		});

		function attachRemoveEvent(button) {
			button.addEventListener('click', function() {
				const rows = document.querySelectorAll('.integrante-row');
				if (rows.length > 1) {
					this.closest('tr').remove();
					// Reordenar numeración
					document.querySelectorAll('.integrante-row').forEach((r, idx) => {
						r.querySelector('td strong').textContent = idx + 1;
					});
				} else {
					alert('Debe conservar al menos un integrante.');
				}
			});
		}

		document.querySelectorAll('.remove-integrante-row').forEach(button => {
			attachRemoveEvent(button);
		});
	});
</script>
@endsection
