<table id="datatable" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Fecha</th>
      <th>Observación</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
  @foreach($feriados as $feriado)
    <tr>
      <td>{!! $feriado->fecha !!}</td>
      <td>{!! $feriado->observacion !!}</td>
      <td>
        {!! Form::open(['route' => ['feriados.destroy', $feriado->id], 'method' => 'delete']) !!}
          <div class='btn-group'>
              <a href="{!! route('feriados.edit', [$feriado->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
              {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')"]) !!}
          </div>
        {!! Form::close() !!}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>