@extends('layouts.panel-abm')

@section('title', 'REPORTE DE OPERADORES')
@section('subtitle', 'Reportes de atención e historial de turnos por operador')

@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">          
          <div class="x_title">
            <h2><i class="fa fa-user-circle-o text-primary"></i> Consulta de Atenciones por Operador</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
              <div class="clearfix"></div>
            @endif
            @include('reporteoperador.table')
          </div>
        </div>
      </div>
    </div>
@stop