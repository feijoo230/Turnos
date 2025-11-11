@extends('layouts.frontend')
@section('content')

<div class="container">
	<div class="row justify-content-md-center">
		<div class="col-md-11">
			<div class="card box-turno">
				<div class="card-body">
					<div class="encabezado">
					  	<ul class="list-group list-group-horizontal-lg">
							<li class="list-group-item"><span class="fa-stack"><span class="far fa-circle fa-2x"></span><span class="fa-stack-1x">#1</span></span> Dirección</li>
							<li class="list-group-item active"><span class="fa-stack"><span class="far fa-circle fa-2x"></span><span class="fa-stack-1x">#2</span></span> Fecha y hora</li>
							<li class="list-group-item"><span class="fa-stack"><span class="far fa-circle fa-2x"></span><span class="fa-stack-1x">#3</span></span> Confirmación</li>
						</ul>
						<p class="titulo">SELECCIONE FECHA Y HORA.</p>
					</div>

					{!! Form::open(['route' => 'tramite.paso3', 'files'=>'true', 'class' => 'form-horizontal text-center', 'method' => 'get']) !!}
						<div class="row">
							<div class="col-md-8 col-sm-12">
								<div class="text-center" style="margin: 0 auto;">
									<div id="datepicker1" style="color: #000000;"></div>
								</div>
							</div>
							<div class="col-md-4 col-sm-12">
								<div id="list-horarios" style="color: #000000;">
									<select name="turno_hora" size="30" class="select list-group overflow-auto text-center" style="max-height: 267px; margin-bottom: 10px; width: 100%; border: 0px;" required="true">
										@foreach($aHorarios as $horario)
											<option class="list-group-item" value="{{ $horario }}">{{ $horario }}</option>
										@endforeach
									</select>

								</div>
							</div>
						</div>
						<!--
						<div class="row">
							<ul style="text-align: left;">
							@foreach($feriados_text as $feriado)
								<div class="col-md-12 col-sm-12">
									<li class="small" style="margin: 0px; color: #000000;">{{$feriado->fecha}} {{ $feriado->observacion }}</li>
								</div>
							@endforeach
							</ul>
						<div></div>
						-->
				</div>
				<div class="card-footer">
					<div style="text-align: right;">
					  	<button onclick="location.href='{!! route('tramite.index') !!}'" class="btn btn-secondary pull-right" type="button">Atrás</button>
					    <button type="submit" class="btn btn-primary">Siguiente</button>
					</div>
				</div>

				<input id="turno_fecha" name="turno_fecha" type="hidden">
					</form>
				
			</div>
		</div>
	</div>
</div>

@stop

@section('script')
<script type="text/javascript">

	var disableddates = <?php echo json_encode($feriados); ?>;

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

	$("#datepicker1").datepicker({
	    onSelect: function() { 
	        var dateObject = $.datepicker.formatDate('dd/mm/yy', $(this).datepicker('getDate'));
	        $('#turno_fecha').val(dateObject);
	        ajax();
	    },
	    beforeShowDay: function(date) {
	    	//inhabilitados los fines de semana
	       var show = true;
	       if(date.getDay()==6||date.getDay()==0) return [false];
	       //inhabilitados los feriados
	       var string = jQuery.datepicker.formatDate('dd/mm/yy', date);
	   	   return [ disableddates.indexOf(string) == -1, 'unavailable']
	    },
	    minDate: "<?= $fecha_desde ?>",
		maxDate: "<?= $turno_dependencia->fecha_hasta->format('d/m/Y') ?>"
	});

	function ajax() {
		$.ajax({
          url:'turnos.loadhorarios',
          data:{
          	'id':"<?= $turno_dependencia->id ?>",
          	"_token": "{{ csrf_token() }}",
          	"turno_fecha": $("#turno_fecha").val()
          },
          type:'post',
          success: function (data) {
          	$('#list-horarios').html(data);
          },
          statusCode: {
             404: function() {
                alert('web not found');
             }
          },
          error:function(x,xs,xt){
              //nos dara el error si es que hay alguno
              window.open(JSON.stringify(x));
              //alert('error: ' + JSON.stringify(x) +"\n error string: "+ xs + "\n error throwed: " + xt);
          }
       });
	}

</script>
@stop