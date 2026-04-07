@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (!isset($mesahabilitada->id))
<div class="form-group" style="margin-bottom: 5px;">
    {!! Form::label('dependencia_id', 'Dependencia:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::select('dependencia_id', $dependencias, null, ['class' => 'form-control']) }}
    </div>
</div>
@else
<div class="form-group">
  {!! Form::label('dependencia_id', 'Dependencia:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    <p style="padding-top: 7px;">{!! $mesahabilitada->dependencia->nombre !!}</p>
  </div>      
</div>
@endif


<div class="form-group">
  {!! Form::label('activo', 'Activo:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::checkbox('activo', TRUE, ((isset($mesahabilitada->activo))? $mesahabilitada->activo : FALSE)) !!}
  </div>      
</div>