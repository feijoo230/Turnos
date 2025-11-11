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
          </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>