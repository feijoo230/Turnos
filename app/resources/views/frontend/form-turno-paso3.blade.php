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
							<li class="list-group-item"><span class="fa-stack"><span class="far fa-circle fa-2x"></span><span class="fa-stack-1x">#2</span></span> Fecha y hora</li>
							<li class="list-group-item active"><span class="fa-stack"><span class="far fa-circle fa-2x"></span><span class="fa-stack-1x">#3</span></span> Confirmación</li>
						</ul>
						<p class="titulo">CONFIRMAR DATOS.</p>
					</div>
					<div class="box-detalle">
						<p class="text-left" style="margin: 0px;"><span class="text-muted">Dirección:</span> {{$dependencia['nombre']}}</p>
			      		<p class="text-left" style="margin: 0px; padding-bottom: 10px;"><span class="text-muted">Fecha y hora:</span> {{$turno_fecha}} {{$turno_hora}}</p>
					</div>

					@if ($errors->any())
					    <div class="alert alert-danger">
					        <ul>
					            @foreach ($errors->all() as $error)
					                <li>{{ $error }}</li>
					            @endforeach
					        </ul>
					    </div>
					@endif

					{!! Form::open(['route' => 'tramite.guardar', 'method' => 'post', 'class' => 'form-horizontal text-left', 'files' => true]) !!}
						<div class="form-group" style="margin-bottom: 5px;">
				          {!! Form::text('nombre_apellido', isset($usuario) ? $usuario->name : null, ['class' => 'form-control', 'placeholder' => 'Nombre y Apellido', 'required' => 'true']) !!}
				        </div>
				        <div class="form-group" style="margin-bottom: 5px;">
				          {!! Form::text('dni', isset($usuario) && $usuario->persona ? $usuario->persona->nro_documento : null, ['class' => 'form-control', 'placeholder' => 'DNI', 'required' => 'true']) !!}
				        </div>
						<div class="form-group" style="margin-bottom: 5px;">
				          {!! Form::text('celular', isset($usuario) && $usuario->persona ? $usuario->persona->tel_movil : null, ['class' => 'form-control', 'placeholder' => 'Celular', 'required' => 'true']) !!}
				        </div>
				        <div class="form-group" style="margin-bottom: 5px;">
				          {!! Form::text('email', isset($usuario) ? $usuario->email : null, ['class' => 'form-control', 'placeholder' => 'Email', 'required' => 'true']) !!}
				        </div>
				        <div class="form-group" style="margin-bottom: 5px;">
				          {!! Form::text('email_confirmation', isset($usuario) ? $usuario->email : null, ['class' => 'form-control', 'placeholder' => 'Confirmar Email', 'required' => 'true']) !!}
				        </div>

						<div class="form-check" style="margin-bottom: 10px; margin-top: 15px;">
							<input class="form-check-input" type="checkbox" name="es_grupal" id="es_grupal" value="1">
							<label class="form-check-label" for="es_grupal">
								¿Es una reserva grupal?
							</label>
						</div>

						<div id="campos_grupales" style="display: none; background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 10px; border: 1px solid #dee2e6;">
							<div class="form-group" style="margin-bottom: 10px;">
								<label for="cantidad_personas">Cantidad de personas</label>
								{!! Form::number('cantidad_personas', 1, ['class' => 'form-control', 'placeholder' => 'Cantidad de personas', 'min' => 1, 'id' => 'cantidad_personas']) !!}
							</div>
							<div class="form-group" style="margin-bottom: 10px;">
								<label for="nombre_institucion">Nombre de la Institución / Colegio</label>
								{!! Form::text('nombre_institucion', null, ['class' => 'form-control', 'placeholder' => 'Ej: Colegio Nacional Nro 1']) !!}
							</div>
							<div class="form-group" style="margin-bottom: 0px;">
								<label for="archivo_integrantes">Importar lista de integrantes (Excel/CSV)</label>
								{!! Form::file('archivo_integrantes', ['class' => 'form-control', 'accept' => '.xls,.xlsx,.csv']) !!}
								<small class="text-muted">El archivo debe tener las columnas: <b>nombre, apellido, dni</b>. <a href="{{ asset('plantilla_integrantes.xlsx') }}" download>Descargar plantilla de ejemplo</a></small>
							</div>
						</div>
				</div>
				<div class="card-footer">
					<div style="text-align: right;">
						<button onclick="location.href='{!! route('tramite.paso2') !!}'" class="btn btn-secondary pull-right" type="button">Atrás</button>
				    	<button type="submit" class="btn btn-primary">Finalizar</button>
					</div>
					</form>
				</div>
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
				$('#campos_grupales').show();
				$('#cantidad_personas').val(2).attr('min', 2).attr('required', true);
				$('input[name="nombre_institucion"]').attr('required', true);
				$('input[name="archivo_integrantes"]').attr('required', true);
			} else {
				$('#campos_grupales').hide();
				$('#cantidad_personas').val(1).attr('min', 1).removeAttr('required');
				$('input[name="nombre_institucion"]').removeAttr('required');
				$('input[name="archivo_integrantes"]').removeAttr('required');
			}
		});
	});
</script>
@endsection