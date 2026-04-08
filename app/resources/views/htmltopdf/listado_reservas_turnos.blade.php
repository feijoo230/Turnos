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
        <tr style="background-color: #f9f9f9;">
          <td style="height: 30px;">{!! $reserva->codigo !!}</td>
          <td style="height: 30px;">{!! $reserva->fecha_hora->format('h:i') !!}</td>
          <td style="height: 30px;">
            {!! $reserva->nombre_apellido !!}
            @if($reserva->es_grupal)
              <br><small><strong>(GRUPO: {!! $reserva->nombre_institucion !!})</strong></small>
            @endif
          </td>
          <td style="height: 30px;">{!! $reserva->dni !!}</td>
          <td style="height: 30px;">{!! $reserva->celular !!}</td>
          <td style="height: 30px;">{!! $reserva->email !!}</td>
        </tr>
        @if($reserva->es_grupal && $reserva->integrantes->count() > 0)
        <tr>
          <td colspan="6" style="padding: 0 0 10px 40px;">
            <table style="width: 100%; font-size: 7pt; border-left: 2px solid #ccc;">
              <thead>
                <tr style="color: #666;">
                  <th align="left">Integrante</th>
                  <th align="left">DNI</th>
                </tr>
              </thead>
              <tbody>
                @foreach($reserva->integrantes as $integrante)
                <tr>
                  <td>{!! $integrante->nombre !!} {!! $integrante->apellido !!}</td>
                  <td>{!! $integrante->dni !!}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </td>
        </tr>
        @endif
        @endforeach
      </tbody>
    </table>
</div>
@stop