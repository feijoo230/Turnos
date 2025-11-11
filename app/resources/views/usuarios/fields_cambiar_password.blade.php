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
    {!! Form::label('name', 'Nombre:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('name', null, ['class' => 'form-control col-md-7 col-xs-12', 'disabled' => true]) !!}
  </div>
</div>
<div class="form-group">
    {!! Form::label('email', 'Email:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('email', null, ['class' => 'form-control col-md-7 col-xs-12', 'disabled' => true]) !!}
  </div>
</div>
<div class="form-group">
    {!! Form::label('password', 'Password:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::password('password', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
  </div>
</div>

{{ (isset($usuario->id))? Form::hidden('id', $usuario->id) : Form::hidden('id', 0) }}