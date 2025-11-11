<table id="datatable" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Dependencia</th>
      <th>Activo</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
  @foreach($tramitesdependencia as $tramitedependencia)
    <tr>
      <td>{!! $tramitedependencia->nombre !!}</td>
      <td>{!! $tramitedependencia->dependencia->nombre !!}</td>
      <td>
        @if ($tramitedependencia->activo == TRUE)
          SI
        @else
          NO
        @endif
      </td>
      <td>
        {!! Form::open(['route' => ['tramitesdependencias.destroy', $tramitedependencia->id], 'method' => 'delete']) !!}
          <div class='btn-group'>
              <a href="{!! route('tramitesdependencias.edit', [$tramitedependencia->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
              {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')"]) !!}
          </div>
        {!! Form::close() !!}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>