<table id="datatable" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
  @foreach($roles as $rol)
    <tr>
      <td>{!! $rol->name !!}</td>
      <td>
        {!! Form::open(['route' => ['roles.destroy', $rol->id], 'method' => 'delete']) !!}
          <div class='btn-group'>
              <a href="{!! route('roles.show', [$rol->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
              <a href="{!! route('roles.edit', [$rol->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
              {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')"]) !!}
          </div>
        {!! Form::close() !!}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>