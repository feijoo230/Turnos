@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <strong class="d-block mb-1"><i class="fa fa-exclamation-triangle"></i> Por favor corrija los siguientes errores:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (!isset($mesahabilitada->id))
    <div class="form-group">
        {!! Form::label('dependencia_id', 'Dependencia / Mesa *:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
        <div class="col-md-6 col-sm-6 col-xs-12">
            {{ Form::select('dependencia_id', ['' => '--- Seleccionar Dependencia a Habilitar ---'] + $dependencias, null, ['class' => 'form-control', 'required' => true]) }}
            <span class="small text-muted">Se muestran las dependencias organizadas por su ruta jerárquica.</span>
        </div>
    </div>
@else
    <div class="form-group">
        {!! Form::label('dependencia_id', 'Dependencia Configurada:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
        <div class="col-md-6 col-sm-6 col-xs-12">
            <p class="form-control-static" style="padding-top: 7px;">
                <i class="fa fa-desktop text-success"></i> 
                <strong>{!! $mesahabilitada->dependencia->string_path ?? $mesahabilitada->dependencia->nombre !!}</strong>
            </p>
        </div>
    </div>
@endif

<div class="form-group">
    {!! Form::label('activo', 'Estado de Atención:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 7px;">
        <label>
            {!! Form::checkbox('activo', 1, ((isset($mesahabilitada->activo))? $mesahabilitada->activo : TRUE)) !!}
            <strong class="text-success"><i class="fa fa-check-circle"></i> Habilitada para otorgar turnos</strong>
        </label>
        <span class="small text-muted d-block">Desmarque esta opción si desea pausar temporalmente la atención sin borrar el registro.</span>
    </div>
</div>