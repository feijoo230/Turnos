@extends('layouts.informe')

@section('content')
<div style="background: #ffffff;">
  <div style="border-bottom: 1px solid #000000; color: #000000; margin-bottom: 20px;">
    <h2 class="text-center">MIS TURNOS RESERVADOS</h2>
    <p class="text-center">DNI: {!! $dni !!}</p>
  </div>
    <table style="font-size: 10pt; width: 100%; color: #000000; border-collapse: collapse;" class="table">   
      <thead>
        <tr style="background-color: #f2f2f2;">
          <th style="border: 1px solid #ddd; padding: 8px;">Código</th>
          <th style="border: 1px solid #ddd; padding: 8px;">Fecha</th>
          <th style="border: 1px solid #ddd; padding: 8px;">Hora</th>
          <th style="border: 1px solid #ddd; padding: 8px;">Dependencia / Trámite</th>
        </tr>
      </thead>           
      <tbody>
        @foreach($reservas as $reserva)
        <tr>
          <td style="border: 1px solid #ddd; padding: 8px;">{!! $reserva->codigo !!}</td>
          <td style="border: 1px solid #ddd; padding: 8px;">{!! $reserva->fecha->format('d/m/Y') !!}</td>
          <td style="border: 1px solid #ddd; padding: 8px;">{!! $reserva->hora !!}</td>
          <td style="border: 1px solid #ddd; padding: 8px;">
            @if ($reserva->turno_horario && $reserva->turno_horario->turno_tramite && $reserva->turno_horario->turno_tramite->tramite)
                {!! $reserva->turno_horario->turno_tramite->tramite->dependencia->nombre !!} - {!! $reserva->turno_horario->turno_tramite->tramite->nombre !!}
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div style="margin-top: 30px; font-size: 9pt; color: #666;">
        <p>Este documento es un comprobante de sus reservas activas al día de la fecha: {!! date('d/m/Y H:i') !!}</p>
    </div>
</div>
@stop
