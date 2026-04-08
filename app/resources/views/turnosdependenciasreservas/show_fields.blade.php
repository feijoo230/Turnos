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
    <div class="form-group">
        {!! Form::label('es_grupal', 'Tipo de Reserva:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
            <p>{!! $reserva->es_grupal ? 'Grupal' : 'Individual' !!}</p>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('cantidad_personas', 'Cantidad de Personas:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
            <p>{!! $reserva->cantidad_personas !!}</p>
        </div>
    </div>
    @if($reserva->nombre_institucion)
    <div class="form-group">
        {!! Form::label('nombre_institucion', 'Institución:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
            <p>{!! $reserva->nombre_institucion !!}</p>
        </div>
    </div>
    @endif
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

    @if($reserva->es_grupal)
    <div class="ln_solid"></div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <h3>Lista de Integrantes ({!! $reserva->integrantes->count() !!})</h3>
        @if($reserva->integrantes->count() > 0)
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reserva->integrantes as $integrante)
                <tr>
                    <td>{!! $integrante->nombre !!}</td>
                    <td>{!! $integrante->apellido !!}</td>
                    <td>{!! $integrante->dni !!}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="alert alert-info">No se cargaron integrantes para esta reserva grupal.</div>
        @endif
    </div>
    @endif
</div>