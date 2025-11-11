@extends('layouts.panel-abm')

@section('title', 'PASES')
@section('subtitle', 'Pases de un tramite en particular.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div>
              <div class="form-group">
                  <p>Nro de Tramite: {!! $tramite->nro_tramite !!}</p>
                  <p>Causante: {!! $tramite->remitente !!}</p>
                  <p>Asunto: {!! $tramite->asunto !!}</p>
              </div>
            </div>
            <div class="ln_solid"></div>
            <div class="title_right">
              <a class="btn btn-primary pull-right" style="margin-bottom: 5px" href="{!! route('pases.create') !!}">Nuevo</a>  
              <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Ingrese su busqueda...">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-primary">Buscar</button>
                  </span>
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @include('pases.table')
          </div>
        </div>
      </div>
    </div>
@stop