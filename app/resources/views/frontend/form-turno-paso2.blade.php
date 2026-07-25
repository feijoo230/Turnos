@extends('layouts.frontend')
@section('content')

<div class="row justify-content-center">
	<div class="col-lg-11 col-xl-10">
		<div class="card box-turno">
			<div class="card-body p-4">
				<!-- Stepper Wizard -->
				<div class="wizard-steps mb-4">
					<div class="wizard-step-item">
						<div class="wizard-step-circle"><i class="fas fa-check text-success"></i></div>
						<span class="wizard-step-title">1. Selección</span>
					</div>
					<div class="wizard-step-item active">
						<div class="wizard-step-circle"><i class="fas fa-calendar-alt"></i></div>
						<span class="wizard-step-title">2. Fecha y Hora</span>
					</div>
					<div class="wizard-step-item">
						<div class="wizard-step-circle">3</div>
						<span class="wizard-step-title">3. Confirmación</span>
					</div>
				</div>

				<div class="text-center mb-4">
					<h4 class="font-weight-bold text-dark mb-1"><i class="fas fa-clock text-success mr-2"></i>Seleccione Fecha y Hora Disponible</h4>
					<p class="text-muted small mb-0">Seleccione un día marcado en el calendario y elija el horario conveniente.</p>
				</div>

				{!! Form::open(['route' => 'tramite.paso3', 'files'=>'true', 'class' => 'form-horizontal', 'method' => 'get']) !!}
					<div class="row align-items-stretch my-3">
						<div class="col-lg-7 col-md-12 mb-4 mb-lg-0 text-center">
							<div class="p-4 bg-light rounded-lg border border-light shadow-xs h-100 d-flex flex-column justify-content-center" style="border-radius: 16px;">
								<h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-calendar-day text-primary mr-2"></i> Calendario de Días Habilitados</h5>
								<div id="datepicker1" class="d-inline-block w-100"></div>
							</div>
						</div>

						<div class="col-lg-5 col-md-12 text-center">
							<div class="p-4 bg-light rounded-lg border border-light shadow-xs h-100 d-flex flex-column justify-content-center" style="border-radius: 16px;">
								<h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-user-clock text-success mr-2"></i> Horarios Disponibles</h5>
								<div id="list-horarios" style="min-height: 280px;" class="d-flex align-items-center justify-content-center">
									<div class="text-muted py-4">
										<i class="fas fa-mouse-pointer fa-3x mb-3 d-block opacity-50 text-primary"></i>
										<span class="font-weight-bold d-block" style="font-size: 1rem;">Haga clic en una fecha habilitada del calendario</span>
										<small class="text-muted">Se desplegarán las franjas horarias disponibles.</small>
									</div>
								</div>
							</div>
						</div>
					</div>

					<input id="turno_fecha" name="turno_fecha" type="hidden">

					<hr class="my-4">

					<div class="d-flex justify-content-between align-items-center">
						<button onclick="location.href='{!! route('tramite.index') !!}'" class="btn btn-outline-secondary font-weight-bold" type="button" style="border-radius: 10px; padding: 10px 22px;">
							<i class="fas fa-arrow-left mr-1"></i> Volver Paso Anterior
						</button>
						<button type="submit" class="btn btn-gradient-primary">
							Continuar a Confirmación <i class="fas fa-arrow-right ml-1"></i>
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
		dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
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
				var dateObject = $.datepicker.formatDate('dd/mm/yy', $(this).datepicker('getDate'));
				$('#turno_fecha').val(dateObject);
				ajax();
			},
			beforeShowDay: function(date) {
				var dayMap = {0: 'domingo', 1: 'lunes', 2: 'martes', 3: 'miercoles', 4: 'jueves', 5: 'viernes', 6: 'sabado'};
				var dayName = dayMap[date.getDay()];
				if (diasActivos && diasActivos[dayName] === false) {
					return [false];
				}
				var string = jQuery.datepicker.formatDate('dd/mm/yy', date);
				var isDisabled = disableddates.indexOf(string) != -1;
				return [ !isDisabled, 'unavailable'];
			},
			minDate: "<?= $fecha_desde ?>",
			maxDate: "<?= $turno_dependencia->fecha_hasta->format('d/m/Y') ?>"
		});
	}

	function ajax() {
		$('#list-horarios').html('<div class="py-4 text-center text-primary"><i class="fas fa-spinner fa-spin fa-2x mb-2"></i><p class="small font-weight-bold mb-0">Cargando horarios disponibles...</p></div>');
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
          },
          error:function(x,xs,xt){
              alert('Ocurrió un error al consultar los horarios. Por favor reintente.');
          }
       });
	}

</script>
@stop