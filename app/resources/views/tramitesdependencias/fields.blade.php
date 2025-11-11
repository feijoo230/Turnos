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
<div class="form-group">
    {!! Form::label('nombre', 'Nombre:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('nombre', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
  </div>
</div>
<div class="form-group">
  {!! Form::label('activo', 'Activo:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::checkbox('activo', true, (isset($tramitedependencia->id)?($tramitedependencia->activo??False):True)) !!}
  </div>      
</div>