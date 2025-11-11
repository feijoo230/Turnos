<div class="row" style="padding-bottom: 150px;">
	<div class="col-md-9 col-lg-7 col-xl-6 mx-auto">
		<p style="font-size: 18px;">CONSULTAR TURNO</p>

		{!! Form::open(['route' => 'turno.buscar', 'method'=> 'get', 'class' => 'form-horizontal text-center']) !!}

			<div class="form-group" style="margin-bottom: 5px;">
				{!! Form::text('codigo_turno', null, ['class' => 'form-control', 'placeholder' => 'Codigo de turno', 'required' => 'true']) !!}
		    </div>
		    <div class="form-group" style="margin-top: 5px;">
	          <div style="text-align: right;">
	            <button type="submit" class="btn btn-primary">Enviar</button>
	          </div>
	        </div>
		</form>

		@if (isset($turno_reserva_busqueda))
			<div id="alert-consulta" class="col-md-10 col-lg-10 col-xl-10 mx-auto">
				<div class="alert alert-primary alert-dismissible fade show" role="alert" style="padding: 10px;">
					<div class="container">
					  	<div class="panel-body">
							<div class="form-group" style="padding-top: 30px;">
							    <h4 class="center-text"><strong>CODIGO DE RESERVA {!! $turno_reserva_busqueda->codigo !!}</strong></h4>
							</div>
							<div class="form-group">
							    <p style="margin: 0px;"><strong>Dirección</strong> {!! $turno_reserva_busqueda->turno_dependencia->dependencia->nombre !!}</p>
							</div>
							<div class="form-group">
							    <p style="margin: 0px;"><strong>Trámite</strong> {!! $turno_reserva_busqueda->turno_tramite->nombre !!}</p>
							</div>
							<div class="form-group">
							    <p><strong>Fecha y hora</strong> {!! $turno_reserva_busqueda->fecha_hora->format('d/m/Y h:i') !!}</p>
							</div>
							<div class="form-group">
							    <p><strong>Nombre y Apellido</strong> {!! $turno_reserva_busqueda->nombre_apellido !!}</p>
							</div>
							<div class="form-group">
							    <p><strong>DNI</strong> {!! $turno_reserva_busqueda->dni !!}</p>
							</div>
							<div class="form-group">
							    <p><strong>Email</strong> {!! $turno_reserva_busqueda->email !!}</p>
							</div>
							<div class="form-group">
								<a href="{!! route('turnos.print', [$turno_reserva_busqueda->id]) !!}" class="center-text"><i class="fas fa-file-pdf fa-2x"></i></br>Descargar comprobante</a>
							</div>
						</div>
					</div>
					<a href="{!! route('tramite.index') !!}" type="button" class="close" aria-label="Close">
				    	<span aria-hidden="true">&times;</span>
				  	</a>
				</div>
			</div>
		@endif

	</div>
</div>