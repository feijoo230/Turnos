{{ Form::open(['route' => 'rolespermisos.store']) }}

  <table id="datatable" class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Nombre</th>
        <th class="text-center">Tiene Permiso</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rol_permisos as $clave => $permiso)
        <tr>
          <td>{!! $permiso['name'] !!}</td>
          <td class="text-center">{{ Form::checkbox('id_permisos[]', $clave, $permiso['tiene_permiso']) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{ Form::hidden('id_role', $role_id) }}
  <div class="ln_solid"></div>
  <div class="form-group">
    <div class="col-md-12 col-sm-12 col-xs-12">
      {!! Form::submit('Guardar', ['class' => 'btn btn-success pull-right']) !!}
    </div>
  </div>
{{ Form::close() }}