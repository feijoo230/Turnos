<table id="datatable" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Dependencia</th>
      <th>Intervalo</th>
      <th>Hora desde</th>
      <th>Hora hasta</th>
      <th>Fecha desde</th>
      <th>Fecha hasta</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
  @foreach($turnosdependencias as $turnosdependencia)
    <tr>
      <td>{!! $turnosdependencia->dependencia->name !!}</td>
      <td>{!! $turnosdependencia->intervalo !!}</td>
      <td>{!! $turnosdependencia->hora_desde !!}</td>
      <td>{!! $turnosdependencia->hora_hasta !!}</td>
      <td>{!! $turnosdependencia->fecha_desde->format('d/m/Y') !!}</td>
      <td>{!! $turnosdependencia->fecha_hasta->format('d/m/Y') !!}</td>
      <td>
        {!! Form::open(['route' => ['turnosdependencias.destroy', $turnosdependencia->id], 'method' => 'delete']) !!}
          <div class='btn-group'>
              <a href="{!! route('turnosdependencias.show', [$turnosdependencia->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
              @if (count($turnosdependencia->reservas) > 0)
                <a class='btn btn-default btn-xs' disabled='true'><i class="fa fa-edit"></i></a>
                {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')", 'disabled' => 'disabled']) !!}
              @else
                <a href="{!! route('turnosdependencias.edit', [$turnosdependencia->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')"]) !!}
              @endif
          </div>
        {!! Form::close() !!}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>