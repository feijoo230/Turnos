<div class="form-horizontal form-label-left">
<div class="form-group">
    {!! Form::label('name', 'Nombre:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
        <p>{!! $usuario->name !!}</p>
    </div>
</div>
<div class="form-group">
    {!! Form::label('email', 'Email:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
        <p>{!! $usuario->email !!}</p>
    </div>
</div>
<div class="form-group">
    {!! Form::label('dependencia_origen_id', 'Dependencia Origen:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
        @if(isset($usuario->dependencias_origen->first()->name))
        <p>{!! $usuario->dependencias_origen->first()->name !!}</p>
        @else
        <p>SIN DEPENDENCIA</p>
        @endif        
    </div>
</div>
<div class="form-group">
    {!! Form::label('dependencias_administradas', 'Dependencias para Administrar:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
        @if($usuario->dependencias->count() > 0)
            @foreach($usuario->dependencias AS $dependencia)
            <span >{{ $dependencia->nombre }}</span>
            <br>
            @endforeach
        @else
            <p>SIN DEPENDENCIAS</p>
        @endif
    </div>
  </div>
</div>