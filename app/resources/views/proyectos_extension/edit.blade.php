@extends('layouts.panel-abm')

@section('title', 'PROYECTOS DE EXTENSIÓN')
@section('subtitle', 'Modificación de Proyecto de Extensión')
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
                {!! Form::model($proyecto, ['route' => ['proyectos-extension.update', $proyecto->id], 'method' => 'patch', 'class' => 'form-horizontal form-label-left', 'files' => true]) !!}
                  
                  <div class="form-group">
                      {!! Form::label('nombre', 'Nombre del Proyecto (*):', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('nombre', null, ['class' => 'form-control', 'required']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('subtitulo', 'Subtítulo / Eje Temático:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('subtitulo', null, ['class' => 'form-control']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('ano', 'Año o Período:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('ano', null, ['class' => 'form-control']) !!}
                      </div>
                  </div>
                  
                  <div class="form-group">
                      {!! Form::label('descripcion', 'Descripción / Resumen:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'rows' => 4]) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('imagen_file', 'Imagen de Portada:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          @if(!empty($proyecto->imagen))
                            <div style="margin-bottom: 10px;">
                                <img src="{{ $proyecto->imagen_url }}" alt="Imagen actual" style="max-height: 120px; border-radius: 6px; border: 1px solid #ddd; padding: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                <p class="small text-muted" style="margin-top: 4px;"><i class="fa fa-info-circle"></i> Imagen actual asignada. Suba otra para reemplazarla.</p>
                            </div>
                          @endif
                          {!! Form::file('imagen_file', ['class' => 'form-control', 'accept' => 'image/*']) !!}
                          <small class="text-muted">Formatos admitidos: JPG, PNG, WEBP (Máx. 5MB).</small>
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
                          {!! Form::number('orden', null, ['class' => 'form-control', 'min' => 0]) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('activo', 'Publicado / Activo:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:8px;">
                          {!! Form::hidden('activo', 0) !!}
                          <label>
                              {!! Form::checkbox('activo', 1, null, ['class' => 'flat']) !!} Visible en el portal público y turnos
                          </label>
                      </div>
                  </div>

                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <button onclick="location.href='{!! route('proyectos-extension.index') !!}'" class="btn btn-default pull-right" type="button" style="margin-left: 10px;">Cancelar</button>
                      <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Actualizar Proyecto</button>
                    </div>
                  </div>
                {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
@stop
