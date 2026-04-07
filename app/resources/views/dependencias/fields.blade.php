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
  {!! Form::label('es_unidad_academica', '¿Es Dependencia?:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::checkbox('es_unidad_academica', 'TRUE', ((isset($dependencia->es_unidad_academica))? $dependencia->es_unidad_academica : FALSE))!!}
  </div>      
</div>
<div class="form-group">
    {!! Form::label('tipo_dependencia_id', 'Se agrupa en:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::select('tipo_dependencia_id', [null => 'Seleccionar...'] + $tipos_dependencias, null , ['class' => 'form-control']) }}
  </div>
</div>
<div class="form-group">
    {!! Form::label('parent_id', 'Padre:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::select('parent_id', [null => 'Seleccionar...'] + $dependencias, null, ['class' => 'form-control']) }}
  </div>
</div>
<div class="form-group">
    {!! Form::label('nombre', 'Nombre:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('nombre', null, ['class' => 'form-control text-uppercase col-md-7 col-xs-12']) !!}
  </div>
</div>
<div class="form-group">
    {!! Form::label('codigo', 'Código:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('codigo', null, ['class' => 'form-control text-uppercase col-md-7 col-xs-12']) !!}
  </div>
</div>
<div class="form-group">
    {!! Form::label('nivel', 'Nivel:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    <span class="small">Por defecto es NIVEL DEL PADRE + 1.</span>
    {!! Form::text('nivel', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
  </div>
</div>