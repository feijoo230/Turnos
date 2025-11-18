<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div style="border: 1px solid #000000; margin: 0 auto; text-align: center; font-family: Arial, Helvetica, sans-serif; max-width: 50%;">
	<div style="margin: 20px;">
		<div style="background: url('{{ public_path().'/img/logounsa1.png' }}') ; background-repeat: repeat-y repeat-x; background-size: 115px;background-position: 20px 20px !important;">
			<div style="padding: 15px;">
			    <h4 style="text-align: center;"><strong>SU CODIGO DE RESERVA ES </strong></h4>
			    <h1 style="text-align: center;">{!! $turno_reserva->codigo !!}</h1>
			</div>
			<div class="form-group">
            <p><strong>FECHA Y HORA</strong> {!! $turno_reserva->fecha_hora_text !!}</p>
        </div>
        	@if ($turno_reserva->turno_tramite && $turno_reserva->turno_tramite->tramite && $turno_reserva->turno_tramite->tramite->dependencia)
			<div class="form-group">
			    <p style="margin: 0px;"><strong>DIRECCIÓN</strong> {!! $turno_reserva->turno_tramite->tramite->dependencia->nombre !!}</p>
			</div>
			@endif
			@if ($turno_reserva->turno_tramite && $turno_reserva->turno_tramite->tramite)
			<div class="form-group">
			    <p style="margin: 0px;"><strong>TRÁMITE</strong> {!! $turno_reserva->turno_tramite->tramite->nombre !!}</p>
			</div>
			@endif
			<p><strong>NOMBRE Y APELLIDO</strong> <?= strtoupper($turno_reserva->nombre_apellido) ?></p>
			<p><strong>DNI</strong> <?= strtoupper($turno_reserva->dni) ?></p> 
			<p><strong>EMAIL</strong> <?= strtoupper($turno_reserva->email) ?></p>
			<div style="border-top: 1px solid #000000;">
				<!--<img src="{{ public_path().'/img/logounsa.png' }}" style="padding-top: 15px; height: 50px;">-->
				<p style="">UNIVERSIDAD NACIONAL DE SALTA</p>
			</div>
		</div>
	</div>
</div>