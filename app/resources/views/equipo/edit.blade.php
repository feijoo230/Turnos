@extends('layouts.panel-abm')

@section('title', 'EQUIPO DE TRABAJO')
@section('subtitle', 'Editar Integrante del Observatorio')
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
                {!! Form::model($miembro, ['route' => ['equipo-trabajo.update', $miembro->id], 'method' => 'patch', 'class' => 'form-horizontal form-label-left', 'files' => true]) !!}
                  
                  <div class="form-group">
                      {!! Form::label('tipo', 'Categoría de Integrante (*):', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <label class="radio-inline" style="margin-right: 20px;">
                              {!! Form::radio('tipo', 'responsable', null, ['required']) !!} <strong>Docente Responsable</strong> (Aparece en tarjetas principales)
                          </label>
                          <label class="radio-inline">
                              {!! Form::radio('tipo', 'colaborador', null, ['required']) !!} <strong>Colaborador</strong> (Aparece en nómina / etiquetas)
                          </label>
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('nombre', 'Nombre y Apellido (*):', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('nombre', null, ['class' => 'form-control', 'required', 'placeholder' => 'Ej: Hugo Sebastián Zerpa / Gómez, María José']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('cargo', 'Cargo o Función:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('cargo', null, ['class' => 'form-control', 'placeholder' => 'Ej: Dirección y Gestión Institucional, Colaborador Didáctico']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('area', 'Área o Especialidad:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('area', null, ['class' => 'form-control', 'placeholder' => 'Ej: Técnica, Didáctica, Comunicación, Gestión']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('user_id', 'Vincular con Usuario del Sistema:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::select('user_id', $users, null, ['class' => 'form-control']) !!}
                          <small class="text-muted"><i class="fa fa-info-circle"></i> Opcional: Puede asociar a un usuario del sistema o dejar en "Ninguno" si es externo o alumno colaborador.</small>
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('email', 'Correo Electrónico:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'correo@unsa.edu.ar']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('biografia', 'Semblanza o Biografía Breve:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::textarea('biografia', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Breve reseña sobre su trayectoria o rol en el Observatorio...']) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('foto_file', 'Fotografía de Perfil:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          @if($miembro->foto_url)
                              <div style="margin-bottom: 10px;">
                                  <img src="{{ $miembro->foto_url }}" alt="{{ $miembro->nombre }}" style="max-height: 90px; border-radius: 6px; border: 1px solid #ccc; padding: 2px;">
                                  <p class="small text-muted" style="margin-top: 4px;">Foto actual registrada. Seleccione un nuevo archivo solo si desea reemplazarla.</p>
                              </div>
                          @endif
                          {!! Form::file('foto_file', ['class' => 'form-control', 'accept' => 'image/jpeg,image/png,image/webp']) !!}
                          <small class="text-muted">Formato JPG, PNG o WebP (Máx. 5MB).</small>
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('orden', 'Orden de Visualización:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::number('orden', null, ['class' => 'form-control', 'min' => 0]) !!}
                          <small class="text-muted">Menor número se visualiza primero dentro de su grupo.</small>
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('activo', 'Publicado / Activo:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:8px;">
                          {!! Form::hidden('activo', 0) !!}
                          <label>
                              {!! Form::checkbox('activo', 1, null, ['class' => 'flat']) !!} Visible en la página pública del Observatorio
                          </label>
                      </div>
                  </div>

                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <a href="{!! route('equipo-trabajo.index') !!}" class="btn btn-default pull-right" style="margin-left: 10px;">Cancelar</a>
                      <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Guardar Cambios</button>
                    </div>
                  </div>
                {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
@endsection
