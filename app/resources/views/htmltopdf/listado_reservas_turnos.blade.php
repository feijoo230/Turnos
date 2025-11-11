@extends('layouts.informe')

@section('content')
<div style="background: #ffffff;">
  <div style="border-bottom: : 1px solid #000000; color: #000000;">
    <h2 class="text-center">RESERVAS DE TURNOS {!! $fecha_turno !!}</h2>
  </div>
    <table style="font-size: 8pt; width: 100%; color: #000000;" class="table-striped">   
      <thead>
        <tr>
          <th>Codigo</th>
          <th>Hora</th>
          <th>Apellido y Nombre</th>
          <th>DNI</th>
          <th>Celular</th>
          <th>Email</th>
        </tr>
      </thead>           
      <tbody>
        @foreach($reservas as $reserva)
        <tr>
          <td style="height: 30px;">{!! $reserva->codigo !!}</td>
          <td style="height: 30px;">{!! $reserva->fecha_hora->format('h:i') !!}</td>
          <td style="height: 30px;">{!! $reserva->nombre_apellido !!}</td>
          <td style="height: 30px;">{!! $reserva->dni !!}</td>
          <td style="height: 30px;">{!! $reserva->celular !!}</td>
          <td style="height: 30px;">{!! $reserva->email !!}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
</div>
@stop