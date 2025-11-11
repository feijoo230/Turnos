@extends('layouts.informe')

@section('content')
<div style="background: #ffffff;">
  <div style="border-bottom: : 1px solid #000000">
    <h3>CONCURSO {!! $expediente->estamentos->name !!} </h3>
  </div>
    <table style="font-size: 10pt; width: 100%;">              
      <tbody>
        <tr>
          <th scope="row"><p>EXP. N°</p></th>
          <td><p>{!! $expediente->numero !!}/{!! $expediente->anio !!}</p></td>
        </tr>
        <tr>
          <th scope="row"><p>Dependencia</p></th>
          <td><p>{!! $expediente->dependencias->name !!}</p></td>
        </tr>
        <tr>
          <th scope="row"><p>Causante</p></th>
          <td><p>{!! $expediente->causante !!}</p></td>
        </tr>
        <tr>
          <th scope="row"><p>Titulo</p></th>
          <td><p>{!! $expediente->titulo !!}</p></td>
        </tr>
        <tr>
          <th scope="row"><p>Cargo</p></th>
          <td><p>{!! $expediente->cargo !!} - CAT. {!! $expediente->categoria !!}</p></td>
        </tr>
      </tbody>
    </table>
    @if (count($expediente->resoluciones->toArray()) > 0)
      <h5><strong>RESOLUCIONES</strong></h5>
      <table class="table" style="font-size: 9pt;">
        <thead>
          <tr>
            <th>#</th>
            <th>RES. N°</th>
            <th>TITULO</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($expediente->resoluciones as $resolucion)
          <tr>
            <td><p></p></td>
            <td><p>{!! $resolucion->numero !!}/{!! $resolucion->anio !!}</p></td>
            <td><p>{!! $resolucion->titulo !!}</p></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p>NO POSEE RESOLUCIONES.</p>
    @endif
</div>
@stop