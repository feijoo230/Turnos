@extends('layouts.panel-abm')

@section('title', 'PROYECTOS DE EXTENSIÓN')
@section('subtitle', 'Alta de Proyecto de Extensión')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul style="margin-bottom: 0;">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <div class="ln_solid"></div>
                {!! Form::open(['route' => 'proyectos-extension.store', 'class' => 'form-horizontal form-label-left', 'files' => true]) !!}
                  
                  <div class="form-group">
                      {!! Form::label('nombre', 'Nombre del Proyecto (*):', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('nombre', null, ['class' => 'form-control', 'required', 'placeholder' => 'Ej: Un cielo en común']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('subtitulo', 'Subtítulo / Eje Temático:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('subtitulo', null, ['class' => 'form-control', 'placeholder' => 'Ej: Astronomía cultural en Tonco']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('ano', 'Año o Período:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('ano', null, ['class' => 'form-control', 'placeholder' => 'Ej: 2023']) !!}
                      </div>
                  </div>
                  
                  <div class="form-group">
                      {!! Form::label('descripcion', 'Descripción / Resumen:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => 'Resumen de objetivos y actividades del proyecto...']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('imagen_file', 'Imagen de Portada:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::file('imagen_file', ['class' => 'form-control', 'accept' => 'image/*']) !!}
                          <small class="text-muted">Formatos admitidos: JPG, PNG, WEBP (Máx. 5MB). Se mostrará en la tarjeta pública.</small>
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('enlace_url', 'Enlace Web / Más Información:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::url('enlace_url', null, ['class' => 'form-control', 'placeholder' => 'https://ejemplo.unsa.edu.ar/...']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('orden', 'Orden de Visualización:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::number('orden', 0, ['class' => 'form-control', 'min' => 0]) !!}
                          <small class="text-muted">Menor valor se muestra primero en el portal público.</small>
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('activo', 'Publicado / Activo:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:8px;">
                          {!! Form::hidden('activo', 0) !!}
                          <label>
                              {!! Form::checkbox('activo', 1, true, ['class' => 'flat']) !!} Visible en el portal público y turnos
                          </label>
                      </div>
                  </div>

                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <button onclick="location.href='{!! route('proyectos-extension.index') !!}'" class="btn btn-default pull-right" type="button" style="margin-left: 10px;">Cancelar</button>
                      <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Guardar Proyecto</button>
                    </div>
                  </div>
                {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
@stop
