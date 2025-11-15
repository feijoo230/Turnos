<div class="form-horizontal form-label-left">
    <div class="form-group">
        {!! Form::label('codigo', 'Código de Reserva:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::text('codigo', null, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('fecha_hora', 'Fecha y Hora:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::text('fecha_hora', $reserva->fecha_hora->format('d/m/Y H:i'), ['class' => 'form-control', 'readonly' => 'readonly']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('nombre_apellido', 'Nombre y Apellido:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::text('nombre_apellido', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('dni', 'DNI:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::text('dni', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('celular', 'Celular:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::text('celular', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('email', 'Email:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::email('email', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('dependencia', 'Dependencia:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::text('dependencia', $reserva->turno_dependencia->dependencia->nombre, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('tramite', 'Trámite:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::text('tramite', $reserva->turno_tramite->nombre, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
        </div>
    </div>
</div>