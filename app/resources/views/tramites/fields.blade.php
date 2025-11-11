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
    {!! Form::label('asunto', 'Asunto:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('asunto', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
  </div>
</div>
<div class="form-group">
    {!! Form::label('remitente', 'Causante:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('remitente', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
  </div>
</div>
<div class="form-group">
    {!! Form::label('domicilio', 'Domicilio:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('domicilio', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
  </div>
</div>
<div class="form-group">
    {!! Form::label('telefono', 'Teléfono:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('telefono', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
  </div>
</div>
<div class="form-group">
    {!! Form::label('correo', 'Email:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('correo', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
  </div>
</div>
<div class="form-group" style="margin-bottom: 5px;">
    {!! Form::label('dependencia_id', 'Dependencia:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::select('dependencia_id', $dependencias, null, ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group" style="margin-bottom: 5px;">
    {!! Form::label('files', 'Archivos:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::file('files[]', ['id' => 'files','class' => 'form-control-file form-control', 'multiple' => 'multiple']) }}
    </div>
</div>