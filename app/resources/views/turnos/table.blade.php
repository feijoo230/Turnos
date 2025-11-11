<p>EN CURSO</p>
<table id="datatable" class="table table-striped table-bordered" style="font-size: 10pt;">
  <thead>
    <tr>
      <th>Fecha Hora</th>
      <th>Nro. DNI</th>
      <th>Nombre/s</th>
      <th>Apellido</th>
      <th>Fecha Hora ingreso</th>
      <th>Fecha Hora egreso</th>
      <th>Tiempo atención</th>
      <th>Operador</th>
      <th>¿Es afiliado?</th>
      <th>Terminar turno</th>
    </tr>
  </thead>
  <tbody>
  @foreach($turno_en_curso as $turno)
    <tr>
      <td>{!! $turno->created_at->format('d/m/Y H:i') !!}</td>
      <td>{!! $turno->cliente->persona->nro_documento !!}</td>
      <td>{!! $turno->cliente->persona->nombre !!}</td>
      <td>{!! $turno->cliente->persona->apellido !!}</td>
      <td>{!! (isset($turno->fecha_hora_ingreso))? $turno->fecha_hora_ingreso->format('d/m/Y H:i'):'' !!}</td>
      <td>{!! (isset($turno->fecha_hora_egreso))? $turno->fecha_hora_egreso->format('d/m/Y H:i') :'' !!}</td>
      <td>{!! $turno->tiempo_atencion !!}</td>
      <td>{!! (isset($turno->operador->name))? $turno->operador->name :'' !!}</td>

      @if ($turno->cliente->es_afiliado == TRUE)
        <td class="text-center"><a href="{!! url('turnos_admin.es_afiliado', [$turno->id]) !!}" title="¿Es afiliado?"><i class="fa fa-check-square-o fa-2x" aria-hidden="true"></i></a></td>
      @else
        <td class="text-center"><a href="{!! url('turnos_admin.es_afiliado', [$turno->id]) !!}" title="¿Es afiliado?"><i class="fa fa-square-o fa-2x" aria-hidden="true"></i></a></td>
      @endif

      <td class="text-center"><a href="{!! url('turnos_admin.terminar_turno', [$turno->id]) !!}" title="Teminar turno"><i class="fa fa-check fa-2x" aria-hidden="true"></i></a></td>
    </tr>
    @endforeach
  </tbody>
</table>
<p>POR ATENDER</p>
<table id="datatable" class="table table-striped table-bordered" style="font-size: 10pt;">
  <thead>
    <tr>
      <th>Fecha Hora</th>
      <th>Nro. DNI</th>
      <th>Nombre/s</th>
      <th>Apellido</th>
      <th>Fecha Hora ingreso</th>
      <th>Fecha Hora egreso</th>
      <th>Tiempo atención</th>
      <th>Operador</th>
      <th>¿Es afiliado?</th>
    </tr>
  </thead>
  <tbody>
  @foreach($turnos as $turno)
    <tr>
      <td>{!! $turno->created_at->format('d/m/Y H:i') !!}</td>
      <td>{!! $turno->cliente->persona->nro_documento !!}</td>
      <td>{!! $turno->cliente->persona->nombre !!}</td>
      <td>{!! $turno->cliente->persona->apellido !!}</td>
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
<p>ATENDIDOS</p>
<table id="datatable" class="table table-striped table-bordered" style="font-size: 10pt;">
  <thead>
    <tr>
      <th>Fecha Hora</th>
      <th>Nro. DNI</th>
      <th>Nombre/s</th>
      <th>Apellido</th>
      <th>Fecha Hora ingreso</th>
      <th>Fecha Hora egreso</th>
      <th>Tiempo atención</th>
      <th>Operador</th>
      <th>¿Es afiliado?</th>
    </tr>
  </thead>
  <tbody>
  @foreach($turnos_atendidos as $turno)
    <tr>
      <td>{!! $turno->created_at->format('d/m/Y H:i') !!}</td>
      <td>{!! $turno->cliente->persona->nro_documento !!}</td>
      <td>{!! $turno->cliente->persona->nombre !!}</td>
      <td>{!! $turno->cliente->persona->apellido !!}</td>
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