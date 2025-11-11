@extends('layouts.informe')
@section('content')
<h3 class="text-center">RESOLUCIÓN DE MODIFICACIÓN DE PLANTA</h3>
  <br>
 <table style="width: 100%;">              
    <tbody>
      <tr>
        <td><p><strong>RESOLUCIÓN. N°</strong></p></td>
        <td><p style="font-size: 18px;"><strong>{!! $resolucion->numero !!}/{!! $resolucion->anio !!}</strong></p></td>
      </tr>
      <tr>
        <td scope="row"><p><strong>TITULO</strong></p></td>
        <td><p>{!! $resolucion->titulo !!}</p></td>
      </tr>
    </tbody>
  </table>
  @if (count($resolucion->resoluciones_abm->toArray()) > 0)
      <h4 class="text-center"><strong>ALTAS/BAJAS/MODIFICANES</strong></h4>
      <br>
      <table width="100%">
        <tbody>
          @foreach ($resolucion->resoluciones_abm as $abm)
          <tr></tr>
          <tr style="border-top: 1px solid #000000;">
            <td><p>{!! $abm->dependencias->name !!} ({!! $abm->acciones->name !!})</p></td>
          </tr>
          <tr></tr>
          <tr>
            <td>
              @if (count($abm->cargos->toArray()) > 0)
                <ul style="tiposfont-size: 12px;">
                @foreach ($abm->cargos as $cargo)
                  
                    <li>
                    <span style="display: inline-block; width: 450px !important;"><strong>{!! $cargo->tipos_cargos->name !!}</strong></span>
                    <span style="display: inline-block; width: 150px;"><strong>{!! $cargo->acciones->name !!}: </strong>{!! $cargo->cantidad !!}</span>                    
                    </li>
                                 
                @endforeach
                </ul>
              @else
                <ul>
                  <li><strong>No posee cargos.<strong></li>
                </ul>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p>NO POSEE ALTAS/BAJAS/MODIFICANES.</p>
    @endif
@stop