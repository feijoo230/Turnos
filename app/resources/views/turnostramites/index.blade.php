@extends('layouts.panel-abm')

@section('title', 'HORARIOS DE ATENCIÓN Y TURNOS')
@section('subtitle', 'Configuración de disponibilidad, fechas y bandas horarias por trámite')

@section('body')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title d-flex justify-content-between align-items-center">
        <h2>
          <i class="fa fa-clock-o text-success"></i> Horarios de Atención por Trámite
          <small>Bandas horarias y cupos</small>
        </h2>
        <div class="title_right text-right">
          <a class="btn btn-primary pull-right" style="margin-bottom: 5px;" href="{!! route('turnostramites.create') !!}">
            <i class="fa fa-plus"></i> Configurar Nuevo Horario
          </a>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        @include('turnostramites.table')
      </div>
    </div>
  </div>
</div>
@stop
