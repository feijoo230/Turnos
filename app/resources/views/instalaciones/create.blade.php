@extends('layouts.panel-abm')

@section('title', 'INSTALACIONES Y EQUIPAMIENTO')
@section('subtitle', 'Alta de Espacio Físico, Telescopio o Instrumental')
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
                {!! Form::open(['route' => 'instalaciones-gestion.store', 'class' => 'form-horizontal form-label-left', 'files' => true]) !!}
                  
                  <div class="form-group">
                      {!! Form::label('nombre', 'Nombre de la Instalación o Equipo (*):', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('nombre', null, ['class' => 'form-control', 'required', 'placeholder' => 'Ej: Cúpula Hemisférica / Telescopio Reflector']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('icono', 'Clase de Ícono (FontAwesome):', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('icono', 'fas fa-university', ['class' => 'form-control', 'placeholder' => 'fas fa-university, fas fa-telescope, fas fa-binoculars']) !!}
                          <small class="text-muted">Sugerencias: <code>fas fa-university</code>, <code>fas fa-telescope</code>, <code>fas fa-binoculars</code>, <code>fas fa-satellite-dish</code>, <code>fas fa-laptop-code</code>.</small>
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('descripcion', 'Descripción Detallada (*):', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'rows' => 4, 'required', 'placeholder' => 'Historia, características técnicas o propósito de la instalación...']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('caracteristicas', 'Características / Puntos Clave (Viñetas):', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::textarea('caracteristicas', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => "Montaje artesanal\nEspacio para grupos reducidos\nDiseño hemisférico optimizado"]) !!}
                          <small class="text-muted"><i class="fa fa-info-circle"></i> Escriba cada punto clave en una línea nueva. En la web pública se mostrarán con una tilde verde.</small>
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('imagen_file', 'Fotografía o Imagen:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::file('imagen_file', ['class' => 'form-control', 'accept' => 'image/jpeg,image/png,image/webp']) !!}
                          <small class="text-muted">Formato JPG, PNG o WebP (Máx. 10MB). Se mostrará a la par del texto descriptivo.</small>
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('orden', 'Orden de Visualización:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::number('orden', 0, ['class' => 'form-control', 'min' => 0]) !!}
                          <small class="text-muted">Menor número se visualiza primero en la página pública.</small>
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('activo', 'Publicado / Activo:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:8px;">
                          {!! Form::hidden('activo', 0) !!}
                          <label>
                              {!! Form::checkbox('activo', 1, true, ['class' => 'flat']) !!} Visible en la página pública del Observatorio
                          </label>
                      </div>
                  </div>

                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <a href="{!! route('instalaciones-gestion.index') !!}" class="btn btn-default pull-right" style="margin-left: 10px;">Cancelar</a>
                      <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Guardar Instalación</button>
                    </div>
                  </div>
                {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
@endsection
