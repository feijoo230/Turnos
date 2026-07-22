@extends('layouts.panel-abm')

@section('title', 'PROYECTOS DE EXTENSIÓN')
@section('subtitle', 'Alta de Proyecto')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              <div class="ln_solid"></div>
                {!! Form::open(['route' => 'proyectos-extension.store', 'class' => 'form-horizontal form-label-left']) !!}
                  
                  <div class="form-group">
                      {!! Form::label('nombre', 'Nombre del Proyecto:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::text('nombre', null, ['class' => 'form-control', 'required']) !!}
                      </div>
                  </div>
                  
                  <div class="form-group">
                      {!! Form::label('descripcion', 'Descripción:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'rows' => 3]) !!}
                      </div>
                  </div>

                  <div class="form-group">
                      {!! Form::label('activo', 'Activo:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:8px;">
                          {!! Form::hidden('activo', 0) !!}
                          {!! Form::checkbox('activo', 1, true, ['class' => 'flat']) !!}
                      </div>
                  </div>

                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <button onclick="location.href='{!! route('proyectos-extension.index') !!}'" class="btn btn-primary pull-right" type="button">Cancelar</button>
                      <button type="submit" class="btn btn-success pull-right">Guardar</button>
                    </div>
                  </div>
                {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
@stop
