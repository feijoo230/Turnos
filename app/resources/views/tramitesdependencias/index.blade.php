@extends('layouts.panel-abm')

@section('title', 'TRÁMITES POR DEPENDENCIA')
@section('subtitle', 'Catálogo de trámites habilitados para solicitud de turnos')

@section('body')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title d-flex justify-content-between align-items-center">
        <h2>
          <i class="fa fa-list-alt text-primary"></i> Trámites por Dependencia
          <small>Trámites disponibles por área</small>
        </h2>
        <div class="title_right text-right">
          <a class="btn btn-primary pull-right" style="margin-bottom: 5px;" href="{!! route('tramitesdependencias.create') !!}">
            <i class="fa fa-plus"></i> Nuevo Trámite
          </a>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        @include('tramitesdependencias.table')
      </div>
    </div>
  </div>
</div>
@stop