@extends('layouts.informe')
@section('content')
@if ($operador_id <> 0)
  @include('reporteoperador.informe-panel-resumen')
@endif
<div class="panel panel-default">
  <div class="panel-heading">LISTADO TURNOS</div>
    <div class="panel-body">
    <table id="datatable" class="table table-striped table-bordered" style="font-size: 10pt;">
      <thead>
        <tr>
          <th>Fecha Hora</th>
          <th>Nro. DNI</th>
          <th>Cliente</th>
          <th>Fecha Hora ingreso</th>
          <th>Fecha Hora egreso</th>
          <th>Tiempo atención (min)</th>
          <th>Operador</th>
          <th>¿Es afiliado?</th>
        </tr>
      </thead>
      <tbody>
      @foreach($turnos as $turno)
        <tr>
          <td>{!! $turno->created_at->format('d/m/Y H:i') !!}</td>
          <td>{!! $turno->cliente->persona->nro_documento !!}</td>
          <td>{!! $turno->cliente->persona->apellido !!}, {!! $turno->cliente->persona->nombre !!}</td>
          <td>{!! (isset($turno->fecha_hora_ingreso))? $turno->fecha_hora_ingreso->format('d/m/Y  H:i') :'' !!}</td>
          <td>{!! (isset($turno->fecha_hora_egreso))? $turno->fecha_hora_egreso->format('d/m/Y  H:i') :'' !!}</td>
          <td>{!! $turno->tiempo_atencion !!}</td>
          <td>{!! (isset($turno->operador->name))? $turno->operador->name :'' !!}</td>
          <td width="15">
            @if ($turno->cliente->es_afiliado == TRUE)
              SI
            @else
              NO
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@stop