<table id="datatable" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Roles</th>
      <th>Email</th>
      <th>Activo</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
  @foreach($usuarios as $usuario)
    <tr>
      <td>{!! $usuario->name !!}</td>
      <td>{!! $usuario->roles()->pluck('name')->implode(' ') !!}</td>
      <td>{!! $usuario->email !!}</td>
      <td width="15">
        @if ($usuario->activo == TRUE)
          SI
        @else
          NO
        @endif
      </td>
      <td>
        {!! Form::open(['route' => ['usuarios.destroy', $usuario->id], 'method' => 'delete']) !!}
          <div class='btn-group'>
              <a href="{!! route('usuarios.show', [$usuario->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye" title="Ver"></i></a>
              <a href="{!! route('usuarios.edit', [$usuario->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit" title="Editar"></i></a>
              <a href="{!! url('usuarios.edit_password', [$usuario->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-key" title="Cambiar Password"></i></a>
              <a href="{!! url('usuariosroles.index', [$usuario->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-sitemap" aria-hidden="true" data-toggle="tooltip" title="Asignar Roles"></i></a>
              <a href="{!! url('usuariosdependencias.index', [$usuario->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-rss" aria-hidden="true" data-toggle="tooltip" title="Asignar Dependencias"></i></a>
              {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')"]) !!}
          </div>
        {!! Form::close() !!}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>