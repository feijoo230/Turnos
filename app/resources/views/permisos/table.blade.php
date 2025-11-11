<table id="datatable" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
  @foreach($permisos as $permiso)
    <tr>
      <td>{!! $permiso->name !!}</td>
      <td>
        {!! Form::open(['route' => ['permisos.destroy', $permiso->id], 'method' => 'delete']) !!}
          <div class='btn-group'>
              <a href="{!! route('permisos.show', [$permiso->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
              <a href="{!! route('permisos.edit', [$permiso->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
              {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')"]) !!}
          </div>
        {!! Form::close() !!}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>