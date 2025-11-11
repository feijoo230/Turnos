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
    {!! Form::label('motivo', 'Motivo:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('motivo', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
  </div>
</div>
<div class="form-group">
    {!! Form::label('usuario_origen_id', 'Usuario Origen:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('usuario_origen_id', $usuario->name, ['class' => 'form-control col-md-7 col-xs-12']) !!}
  </div>
</div>
<div class="form-group">
    {!! Form::label('dependencia_origen_id', 'Dependencia Origen:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('dependencia_origen_id', $usuario->dependencias_origen->first()->nombre, ['class' => 'form-control col-md-7 col-xs-12']) !!}
  </div>
</div>
<div class="form-group">
    {!! Form::label('dependencia_destino_id', 'Dependencia Destino:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::select('dependencia_destino_id', $dependencias, null, ['class' => 'form-control']) }}
  </div>
</div>
<div class="form-group">
    {!! Form::label('dependencia_destino_id', 'Usuario Destino:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('dependencia_destino_id', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
  </div>
</div>
<div class="panel-body">
    <autocomplete></autocomplete>
</div>
{{ (isset($tramite->id))? Form::hidden('$tramite_id', $tramite->id) : Form::hidden('tramite_id', 0) }}