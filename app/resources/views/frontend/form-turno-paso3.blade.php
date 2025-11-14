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
					{!! Form::open(['route' => 'tramite.guardar', 'class' => 'form-horizontal text-left']) !!}
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