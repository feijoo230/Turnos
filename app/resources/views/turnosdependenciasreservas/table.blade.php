<table id="datatable" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Codigo</th>
      <th>Fecha hora</th>
      <th>Apellido y Nombre</th>
      <th>DNI</th>
      <th>Celular</th>
      <th>Email</th>
      <th>Dependencia</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
  @foreach($reservas as $reserva)
    <tr>
      <td>{!! $reserva->codigo !!}</td>
      <td>{!! $reserva->fecha_hora->format('d/m/Y h:i') !!}</td>
      <td>{!! $reserva->nombre_apellido !!}</td>
      <td>{!! $reserva->dni !!}</td>
      <td>{!! $reserva->celular !!}</td>
      <td>{!! $reserva->email !!}</td>
      <td>{!! $reserva->turno_dependencia->dependencia->nombre !!}</td>
      <td>
          <div class='btn-group'>
              <a href="{!! route('turnosdependenciasreservas.show', [$reserva->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
              <a href="{!! route('turnosdependenciasreservas.edit', [$reserva->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
              <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal" data-id="{{ $reserva->id }}" data-url="{{ route('turnosdependenciasreservas.destroy', $reserva->id) }}"><i class="fa fa-trash"></i></a>
          </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que quieres eliminar esta reserva?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="deleteConfirmBtn">Eliminar</button>
      </div>
    </div>
  </div>
</div>