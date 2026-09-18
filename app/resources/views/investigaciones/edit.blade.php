@extends('layouts.panel-abm')

@section('title', 'INVESTIGACIÓN Y PUBLICACIONES')
@section('subtitle', 'Modificación de Publicación / Artículo de Investigación')
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
                {!! Form::model($investigacion, ['route' => ['investigaciones.update', $investigacion->id], 'method' => 'patch', 'class' => 'form-horizontal form-label-left', 'files' => true]) !!}
                  
                  <div class="form-group">
                      {!! Form::label('titulo', 'Título de la Publicación (*):', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('titulo', null, ['class' => 'form-control', 'required']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('revista', 'Revista / Cita Bibliográfica:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('revista', null, ['class' => 'form-control']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('autores', 'Autores / Investigadores:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('autores', null, ['class' => 'form-control']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('ano', 'Año de Publicación:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('ano', null, ['class' => 'form-control']) !!}
                      </div>
                  </div>
                  
                  <div class="form-group">
                      {!! Form::label('descripcion', 'Resumen / Abstract:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'rows' => 4]) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('archivo_pdf_file', 'Documento PDF (Adjunto):', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          @if(!empty($investigacion->archivo_pdf))
                            <div style="margin-bottom: 10px;">
                                <a href="{{ $investigacion->pdf_url }}" target="_blank" class="btn btn-sm btn-danger">
                                    <i class="fa fa-file-pdf-o"></i> Ver PDF actual adjunto
                                </a>
                                <p class="small text-muted" style="margin-top: 4px;"><i class="fa fa-info-circle"></i> Ya cuenta con un PDF adjunto. Suba otro archivo solo si desea reemplazarlo.</p>
                            </div>
                          @endif
                          {!! Form::file('archivo_pdf_file', ['class' => 'form-control', 'accept' => 'application/pdf']) !!}
                          <small class="text-muted">Formatos admitidos: PDF (Máx. 20MB).</small>
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('enlace_url', 'Enlace Web Externo (DOI / Portal de Revista):', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::url('enlace_url', null, ['class' => 'form-control', 'placeholder' => 'https://revistas.unc.edu.ar/...']) !!}
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
                              {!! Form::checkbox('activo', 1, null, ['class' => 'flat']) !!} Visible en el portal público
                          </label>
                      </div>
                  </div>

                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <button onclick="location.href='{!! route('investigaciones.index') !!}'" class="btn btn-default pull-right" type="button" style="margin-left: 10px;">Cancelar</button>
                      <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Actualizar Publicación</button>
                    </div>
                  </div>
                {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
@stop
