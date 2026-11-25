@extends('layouts.frontend')
@section('content')

<style>
	.calendar-legend {
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		gap: 14px;
		font-size: 0.82rem;
		color: #475569;
		margin-top: 14px;
	}
	.legend-item {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		font-weight: 500;
	}
	.legend-dot {
		width: 14px;
		height: 14px;
		border-radius: 4px;
		display: inline-block;
	}
	.legend-dot-available {
		background: #e0f2fe;
		border: 1.5px solid #38bdf8;
	}
	.legend-dot-selected {
		background: #059669;
		border: 1.5px solid #047857;
	}
	.legend-dot-disabled {
		background: #f1f5f9;
		border: 1px solid #cbd5e1;
	}
	.slots-grid {
		display: grid;
		grid-template-columns: 1fr;
		gap: 10px;
		max-height: 310px;
		overflow-y: auto;
		padding-right: 4px;
	}
	.slot-option {
		display: block;
		margin: 0;
		cursor: pointer;
	}
	.slot-option input[type="radio"] {
		position: absolute;
		opacity: 0;
		pointer-events: none;
	}
	.slot-card {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 13px 16px;
		background: #ffffff;
		border: 2px solid #cbd5e1;
		border-radius: 12px;
		transition: all 0.18s ease;
	}
	.slot-option:hover .slot-card {
		border-color: #38bdf8;
		background: #f0f9ff;
	}
	.slot-option input[type="radio"]:checked + .slot-card {
		border-color: #059669;
		background: #ecfdf5;
		box-shadow: 0 4px 12px rgba(5, 150, 105, 0.18);
	}
	.slot-card-left {
		display: flex;
		flex-direction: column;
		text-align: left;
	}
	.slot-time {
		font-weight: 700;
		font-size: 1.08rem;
		color: #0f172a;
	}
	.slot-seats {
		font-size: 0.82rem;
		color: #475569;
		font-weight: 500;
	}
	.slot-check {
		font-size: 1.3rem;
		color: #059669;
		opacity: 0.18;
		transition: all 0.18s ease;
	}
	.slot-option input[type="radio"]:checked + .slot-card .slot-check {
		opacity: 1;
		transform: scale(1.08);
	}
	.selection-pill-paso2 {
		background: #f8fafc;
		border: 1px solid #cbd5e1;
		border-radius: 12px;
		padding: 12px 16px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-wrap: wrap;
		gap: 10px;
	}
	.selection-pill-paso2.is-ready {
		background: #ecfdf5;
		border-color: #6ee7b7;
	}
</style>

<div class="row justify-content-center">
	<div class="col-lg-11 col-xl-10">
		<div class="card box-turno">
			<div class="card-body p-4">
				<!-- Stepper Wizard -->
				<div class="wizard-steps mb-4">
					<div class="wizard-step-item completed">
						<div class="wizard-step-circle"><i class="fas fa-check"></i></div>
						<span class="wizard-step-title">1. Actividad</span>
					</div>
					<div class="wizard-step-item active">
						<div class="wizard-step-circle"><i class="fas fa-calendar-alt"></i></div>
						<span class="wizard-step-title">2. Día y Hora</span>
					</div>
					<div class="wizard-step-item">
						<div class="wizard-step-circle">3</div>
						<span class="wizard-step-title">3. Tus Datos</span>
					</div>
				</div>

				<!-- Resumen de lo elegido en el Paso 1 -->
				@if(isset($dependencia) || isset($dependencia_tramite))
					<div class="p-3 mb-4 d-flex justify-content-between align-items-center flex-wrap" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 12px; gap: 10px;">
						<div class="d-flex align-items-center" style="gap: 12px;">
							<div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px; background: #0284c7; color: #ffffff;">
								<i class="fas fa-university"></i>
							</div>
							<div>
								<small class="text-uppercase font-weight-bold text-primary d-block" style="font-size: 0.72rem; letter-spacing: 0.5px;">Estás reservando</small>
								<span class="font-weight-bold text-dark">
									{{ isset($dependencia) && $dependencia ? $dependencia->nombre : '' }}
									@if(isset($dependencia_tramite) && $dependencia_tramite)
										<i class="fas fa-angle-right mx-1 text-primary"></i> {{ $dependencia_tramite->nombre }}
									@endif
								</span>
							</div>
						</div>
						<a href="{{ route('tramite.index') }}" class="btn btn-sm btn-outline-primary font-weight-bold" style="border-radius: 8px;">
							<i class="fas fa-exchange-alt mr-1"></i> Cambiar actividad
						</a>
					</div>
				@endif

				<div class="text-center mb-4">
					<h4 class="font-weight-bold text-dark mb-1">
						<i class="fas fa-clock text-success mr-2"></i>Elegí el Día y el Horario
					</h4>
					<p class="text-muted small mb-0">
						Seleccioná un día disponible en el calendario y elegí la franja horaria.
					</p>
				</div>

				<!-- Alerta amigable inline -->
				<div id="paso2-warning" class="alert alert-warning border-0 shadow-xs mb-4" style="display: none; border-radius: 12px; background: #fffbeb; color: #92400e; border-left: 4px solid #f59e0b !important;">
					<i class="fas fa-exclamation-circle mr-2"></i>
					<span id="paso2-warning-text"></span>
				</div>

				{!! Form::open(['route' => 'tramite.paso3', 'class' => 'form-horizontal', 'method' => 'get', 'id' => 'form-paso2']) !!}
					<div class="row align-items-stretch my-3">
						<div class="col-lg-7 col-md-12 mb-4 mb-lg-0 text-center">
							<div class="p-4 bg-light rounded-lg border h-100 d-flex flex-column justify-content-between" style="border-radius: 16px; border-color: #e2e8f0 !important;">
								<div>
									<h5 class="font-weight-bold text-dark mb-3">
										<i class="fas fa-calendar-day text-primary mr-2"></i>1. Elegí una Fecha
									</h5>
									<div id="datepicker1" class="d-inline-block w-100"></div>
								</div>

								<!-- Leyenda visual del calendario -->
								<div class="calendar-legend pt-3 border-top">
									<span class="legend-item"><span class="legend-dot legend-dot-available"></span> Con turnos</span>
									<span class="legend-item"><span class="legend-dot legend-dot-selected"></span> Día elegido</span>
									<span class="legend-item"><span class="legend-dot legend-dot-disabled"></span> Sin turnos / Feriado</span>
								</div>
							</div>
						</div>

						<div class="col-lg-5 col-md-12 text-center">
							<div class="p-4 bg-light rounded-lg border h-100 d-flex flex-column" style="border-radius: 16px; border-color: #e2e8f0 !important;">
								<h5 class="font-weight-bold text-dark mb-1">
									<i class="fas fa-user-clock text-success mr-2"></i>2. Elegí un Horario
								</h5>
								<small id="fecha-elegida-subtitulo" class="text-primary font-weight-bold d-block mb-3" style="min-height: 20px;"></small>

								<div id="list-horarios" style="min-height: 280px;" class="flex-grow-1 d-flex align-items-center justify-content-center">
									<div class="text-muted py-4 px-2">
										<i class="fas fa-hand-point-left fa-3x mb-3 d-block text-primary" style="opacity: 0.55;"></i>
										<span class="font-weight-bold d-block text-dark mb-1" style="font-size: 1rem;">Tocá una fecha celeste en el calendario</span>
										<small class="text-muted">En cuanto elijas un día, aquí aparecerán los horarios disponibles para reservar.</small>
									</div>
								</div>
							</div>
						</div>
					</div>

					<input id="turno_fecha" name="turno_fecha" type="hidden">

					<!-- Barra de resumen de fecha y hora elegidas -->
					<div id="paso2-summary" class="selection-pill-paso2 mt-3 mb-2">
						<div class="d-flex align-items-center" style="gap: 10px;">
							<i id="paso2-summary-icon" class="fas fa-calendar text-muted fa-lg"></i>
							<div>
								<small class="text-uppercase font-weight-bold text-muted d-block" style="font-size: 0.72rem; letter-spacing: 0.5px;">Día y horario elegido</small>
								<span id="paso2-summary-text" class="font-weight-bold text-dark">Todavía no elegiste fecha ni horario.</span>
							</div>
						</div>
					</div>

					<hr class="my-4">

					<div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 12px;">
						<a href="{!! route('tramite.index') !!}" class="btn btn-outline-secondary font-weight-bold" style="border-radius: 10px; padding: 10px 22px;">
							<i class="fas fa-arrow-left mr-1"></i> Volver al Paso 1
						</a>
						<button type="submit" id="btn-paso2-next" class="btn btn-gradient-primary btn-lg">
							Siguiente: Completar tus Datos <i class="fas fa-arrow-right ml-2"></i>
						</button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop

@section('script')
<script type="text/javascript">

	var disableddates = <?php echo json_encode($feriados); ?>;
	var selectedDateText = '';
	var selectedHoraCorta = '';

	function showPaso2Warning(msg) {
		$('#paso2-warning-text').text(msg);
		$('#paso2-warning').slideDown(200);
		$('html, body').animate({ scrollTop: $('#paso2-warning').offset().top - 100 }, 250);
	}

	function hidePaso2Warning() {
		$('#paso2-warning').hide();
	}

	function updatePaso2Summary() {
		var $bar = $('#paso2-summary');
		var $icon = $('#paso2-summary-icon');
		var $text = $('#paso2-summary-text');

		if (selectedDateText && selectedHoraCorta) {
			$bar.addClass('is-ready');
			$icon.attr('class', 'fas fa-check-circle text-success fa-lg');
			$text.html('Fecha: <strong>' + selectedDateText + '</strong> a las <strong>' + selectedHoraCorta + ' hs</strong>. Ya podés avanzar al último paso.');
		} else if (selectedDateText) {
			$bar.removeClass('is-ready');
			$icon.attr('class', 'fas fa-clock text-primary fa-lg');
			$text.html('Fecha elegida: <strong>' + selectedDateText + '</strong>. Ahora tocá uno de los horarios disponibles.');
		} else {
			$bar.removeClass('is-ready');
			$icon.attr('class', 'fas fa-calendar text-muted fa-lg');
			$text.text('Todavía no elegiste fecha ni horario.');
		}
	}

	$.ajax({
		url: '{{ route("turnos.getdisableddates") }}',
		type: 'post',
		data: {
			'id': "<?= $turno_dependencia->id ?>",
			"_token": "{{ csrf_token() }}"
		},
		success: function(data) {
			disableddates = disableddates.concat(data);
			initDatepicker();
		},
		error: function() {
			initDatepicker();
		}
	});

	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '< Ant',
		nextText: 'Sig >',
		currentText: 'Hoy',
		monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
		dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
		dayNamesShort: ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''
	};
	$.datepicker.setDefaults($.datepicker.regional['es']);

	var diasActivos = <?php echo json_encode($dias_activos ?? []); ?>;

	function initDatepicker() {
		$("#datepicker1").datepicker({
			onSelect: function() {
				hidePaso2Warning();
				var dateObject = $.datepicker.formatDate('dd/mm/yy', $(this).datepicker('getDate'));
				$('#turno_fecha').val(dateObject);
				selectedDateText = dateObject;
				selectedHoraCorta = '';
				$('#fecha-elegida-subtitulo').html('<i class="fas fa-calendar-check mr-1"></i> Horarios para el ' + dateObject);
				updatePaso2Summary();
				ajax();
			},
			beforeShowDay: function(date) {
				var dayMap = {0: 'domingo', 1: 'lunes', 2: 'martes', 3: 'miercoles', 4: 'jueves', 5: 'viernes', 6: 'sabado'};
				var dayName = dayMap[date.getDay()];
				if (diasActivos && diasActivos[dayName] === false) {
					return [false, 'unavailable', 'Sin atención este día'];
				}
				var string = jQuery.datepicker.formatDate('dd/mm/yy', date);
				var isDisabled = disableddates.indexOf(string) != -1;
				if (isDisabled) {
					return [false, 'unavailable', 'Sin turnos disponibles'];
				}
				return [true, 'day-available', 'Día con turnos disponibles'];
			},
			minDate: "<?= $fecha_desde ?>",
			maxDate: "<?= $turno_dependencia->fecha_hasta->format('d/m/Y') ?>"
		});
	}

	function ajax() {
		$('#list-horarios').html('<div class="py-4 text-center text-primary"><i class="fas fa-spinner fa-spin fa-2x mb-2 d-block"></i><p class="small font-weight-bold mb-0">Buscando horarios disponibles...</p></div>');
		$.ajax({
          url:'{{ route("turnos.loadhorarios") }}',
          data:{
          	'id':"<?= $turno_dependencia->id ?>",
          	"_token": "{{ csrf_token() }}",
          	"turno_fecha": $("#turno_fecha").val()
          },
          type:'post',
          success: function (data) {
          	$('#list-horarios').html(data);
          	var $radios = $('#list-horarios input[name="turno_hora"]');
          	if ($radios.length === 1) {
          		$radios.first().prop('checked', true).trigger('change');
          	}
          },
          error: function(){
              $('#list-horarios').html('<div class="alert alert-danger mb-0 small" style="border-radius: 10px;"><i class="fas fa-exclamation-triangle mr-1"></i> Ocurrió un error al consultar los horarios. Por favor tocá nuevamente la fecha.</div>');
          }
       });
	}

	$(document).on('change', 'input[name="turno_hora"]', function() {
		hidePaso2Warning();
		selectedHoraCorta = $(this).data('hora-corta') || $(this).val().split('|')[0].substring(0, 5);
		updatePaso2Summary();
	});

	$('#form-paso2').on('submit', function(e) {
		var fecha = $('#turno_fecha').val();
		var hora = $('input[name="turno_hora"]:checked').val();

		if (!fecha) {
			e.preventDefault();
			showPaso2Warning('Primero tocá un día pintado de celeste en el calendario.');
			return false;
		}
		if (!hora) {
			e.preventDefault();
			showPaso2Warning('Por favor tocá uno de los horarios disponibles a la derecha antes de continuar.');
			return false;
		}
		$('#btn-paso2-next').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Cargando formulario...');
	});

</script>
@stop