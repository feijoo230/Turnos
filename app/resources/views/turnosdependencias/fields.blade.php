@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="form-group">
    {!! Form::label('dependencia_id', 'Dependencia:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::select('dependencia_id', $dependencias, null, ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group" style="margin-bottom: 5px;">
    {!! Form::label('intervalo', 'Intervalo (minutos):', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::select('intervalo', [
            '5' => '5',
            '10' => '10',
            '15' => '15',
            '20' => '20',
            '25' => '25',
            '30' => '30',
            '35' => '35',
            '40' => '40',
            '45' => '45',
            '50' => '50',
            '55' => '55',
            '60' => '60'
        ], null, ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group" style="margin-bottom: 5px;">
    {!! Form::label('hora_desde', 'Hora desde:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('hora_desde', isset($turnodependencia)? \Carbon\Carbon::createFromFormat('H:i:s',$turnodependencia->hora_desde)->format('h:i') : null, ['class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'hh:mm']) !!}
    </div>
</div>
<div class="form-group" style="margin-bottom: 5px;">
    {!! Form::label('hora_hasta', 'Hora hasta:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('hora_hasta', isset($turnodependencia)? \Carbon\Carbon::createFromFormat('H:i:s',$turnodependencia->hora_hasta)->format('h:i') : null, ['class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'hh:mm']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('fecha_desde', 'Fecha desde:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('fecha_desde', isset($turnodependencia)? $turnodependencia->fecha_desde->format('d/m/Y') : null, ['class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'dd/mm/aaaa']) !!}
  </div>
</div>
<div class="form-group">
    {!! Form::label('fecha_hasta', 'Fecha hasta:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('fecha_hasta', isset($turnodependencia)? $turnodependencia->fecha_hasta->format('d/m/Y') : null, ['class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'dd/mm/aaaa']) !!}
  </div>
</div>
<div class="form-group">
  {!! Form::label('lunes', 'Lunes', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::checkbox('lunes', '1', ((isset($dependencia->lunes))? $dependencia->lunes : TRUE)); !!}
  </div>      
</div>
<div class="form-group">
  {!! Form::label('martes', 'Martes', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::checkbox('martes', '1', ((isset($dependencia->martes))? $dependencia->martes : TRUE)); !!}
  </div>      
</div>
<div class="form-group">
  {!! Form::label('miercoles', 'Miercoles', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::checkbox('miercoles', '1', ((isset($dependencia->miercoles))? $dependencia->miercoles : TRUE)); !!}
  </div>      
</div>
<div class="form-group">
  {!! Form::label('jueves', 'Jueves', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::checkbox('jueves', '1', ((isset($dependencia->jueves))? $dependencia->jueves : TRUE)); !!}
  </div>      
</div>
<div class="form-group">
  {!! Form::label('viernes', 'Viernes', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::checkbox('viernes', '1', ((isset($dependencia->viernes))? $dependencia->viernes : TRUE)); !!}
  </div>      
</div>

{{ Form::hidden('activo', 1) }}