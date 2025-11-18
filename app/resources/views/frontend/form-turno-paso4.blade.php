@extends('layouts.frontend')
@section('content')

<div class="container">
	<div class="row justify-content-md-center">
		<div class="col-md-11">
			<div class="card box-turno">
				<div class="card-body">
					<div class="encabezado">
						<p class="titulo">EL TURNO SE REGISTRO CON EXITO.</p>
						<p class="titulo small">¡IMPORTANTE! PRESENTE EL COMPROBANTE DEL TURNO.</p>
					</div>
					<div style="color: #000000;">
						<div class="form-group" style="padding-top: 5px;">
						    <h4 class="center-text"><strong>SU CODIGO DE RESERVA ES {!! $turno_reserva->codigo !!}</strong></h4>
						</div>
						<div class="form-group">
							<a href="{!! route('turnos.print', [$turno_reserva->id]) !!}" class="center-text"><i class="fas fa-file-pdf fa-2x"></i></br>Descargar comprobante</a>
						</div>
						@if ($turno_reserva->turno_tramite && $turno_reserva->turno_tramite->tramite && $turno_reserva->turno_tramite->tramite->dependencia)
						<div class="form-group">
						    <p style="margin: 0px;"><strong>Dirección</strong> {!! $turno_reserva->turno_tramite->tramite->dependencia->nombre !!}</p>
						</div>
						@endif
						@if ($turno_reserva->turno_tramite && $turno_reserva->turno_tramite->tramite)
						<div class="form-group">
						    <p style="margin: 0px;"><strong>Trámite</strong> {!! $turno_reserva->turno_tramite->tramite->nombre !!}</p>
						</div>
						@endif
						<div class="form-group">
						    <p style="margin: 0px;"><strong>Fecha y hora</strong> {!! $turno_reserva->fecha_hora->format('d/m/Y h:i') !!}</p>
						</div>
						<div class="form-group">
						    <p style="margin: 0px;"><strong>Nombre y Apellido</strong> {!! $turno_reserva->nombre_apellido !!}</p>
						</div>
						<div class="form-group">
						    <p style="margin: 0px;"><strong>DNI</strong> {!! $turno_reserva->dni !!}</p>
						</div>
						<div class="form-group">
						    <p style="margin: 0px;"><strong>Email</strong> {!! $turno_reserva->email !!}</p>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div style="text-align: right;">
						<button onclick="location.href='{!! route('turnos') !!}'" class="btn btn-primary pull-right" type="button">Aceptar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop