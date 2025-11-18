<div class="form-horizontal form-label-left">
    <div class="form-group">
        {!! Form::label('codigo', 'Código de Reserva:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
            <p>{!! $reserva->codigo !!}</p>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('fecha_hora', 'Fecha y Hora:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
            <p>{!! $reserva->fecha_hora->format('d/m/Y H:i') !!}</p>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('nombre_apellido', 'Nombre y Apellido:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
            <p>{!! $reserva->nombre_apellido !!}</p>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('dni', 'DNI:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
            <p>{!! $reserva->dni !!}</p>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('celular', 'Celular:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
            <p>{!! $reserva->celular !!}</p>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('email', 'Email:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
            <p>{!! $reserva->email !!}</p>
        </div>
    </div>
    @if ($reserva->turno_tramite && $reserva->turno_tramite->tramite && $reserva->turno_tramite->tramite->dependencia)
    <div class="form-group">
        {!! Form::label('dependencia', 'Dependencia:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
            <p>{!! $reserva->turno_tramite->tramite->dependencia->nombre !!}</p>
        </div>
    </div>
    @endif
    @if ($reserva->turno_tramite && $reserva->turno_tramite->tramite)
    <div class="form-group">
        {!! Form::label('tramite', 'Trámite:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
            <p>{!! $reserva->turno_tramite->tramite->nombre !!}</p>
        </div>
    </div>
    @endif
</div>