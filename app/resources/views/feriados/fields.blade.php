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
    {!! Form::label('fecha', 'Fecha:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('fecha', isset($feriado)? $feriado->fecha : null, ['class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'dd/mm/aaaa']) !!}
  </div>
</div>
<div class="form-group">
    {!! Form::label('observacion', 'Observación:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('observacion', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
  </div>
</div>
<div class="form-group">
  {!! Form::label('activo', 'Activo:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::checkbox('activo', 1, ((isset($feriado->activo))? $feriado->activo : 1)) !!}
  </div>      
</div>