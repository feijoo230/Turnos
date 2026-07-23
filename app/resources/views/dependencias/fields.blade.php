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

<div class="form-group">
    {!! Form::label('nombre', 'Nombre *:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('nombre', null, ['class' => 'form-control text-uppercase', 'required' => true, 'placeholder' => 'EJ: DEPARTAMENTO DE ALUMNOS']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('codigo', 'Código:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('codigo', null, ['class' => 'form-control text-uppercase', 'placeholder' => 'EJ: FCE-ALU']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('parent_id', 'Dependencia Padre:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::select('parent_id', [null => '--- Ninguna (Es Dependencia Raíz) ---'] + $dependencias, null, ['class' => 'form-control']) }}
        <span class="small text-muted">Seleccione si pertenece a otra facultad, departamento o rectorado.</span>
    </div>
</div>

<div class="form-group">
    {!! Form::label('tipo_dependencia_id', 'Agrupación / Tipo:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::select('tipo_dependencia_id', [null => '--- Seleccionar Agrupación ---'] + $tipos_dependencias, null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('nivel', 'Nivel Jerárquico:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('nivel', null, ['class' => 'form-control', 'placeholder' => 'Por defecto: Nivel del Padre + 1', 'min' => 1]) !!}
        <span class="small text-muted">Si se deja en blanco, se calculará automáticamente según el padre.</span>
    </div>
</div>

<div class="form-group">
    {!! Form::label('es_unidad_academica', '¿Es Unidad Académica?:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 7px;">
        <label>
            {!! Form::checkbox('es_unidad_academica', 1, ((isset($dependencia->es_unidad_academica))? $dependencia->es_unidad_academica : FALSE)) !!}
            <strong class="text-primary"><i class="fa fa-university"></i> Es Facultad / Sede Principal</strong>
        </label>
    </div>
</div>