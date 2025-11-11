<table id="datatable" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Mesas</th>
      <th>Activo</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
  @foreach($mesashabilitadas as $mesahabilitada)
    <tr>
      <td>{!! $mesahabilitada->dependencia->name !!}</td>
      <td width="15">
        @if ($mesahabilitada->activo == TRUE)
          SI
        @else
          NO
        @endif
      </td>
      <td>
        {!! Form::open(['route' => ['mesashabilitadas.destroy', $mesahabilitada->id], 'method' => 'delete']) !!}
          <div class='btn-group'>
            <a href="{!! route('mesashabilitadas.edit', [$mesahabilitada->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
            {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')"]) !!}
          </div>
        {!! Form::close() !!}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>