<table id="datatable" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th style="width: 30px;"><input type="checkbox" id="select-all"></th>
      <th>Codigo</th>
      <th>Fecha hora</th>
      <th>Apellido y Nombre</th>
      <th>DNI</th>
      <th>Personas</th>
      <th>Institución</th>
      <th>Dependencia</th>
      <th class="text-center">Estado</th>
      <th class="text-center" style="width: 140px;">Acciones</th>
    </tr>
  </thead>
  <tbody>
  @foreach($reservas as $reserva)
    <tr>
      <td><input type="checkbox" class="select-item" value="{{ $reserva->id }}"></td>
      <td><strong>{!! $reserva->codigo !!}</strong></td>
      <td>{!! $reserva->fecha_hora->format('d/m/Y H:i') !!} hs</td>
      <td>{!! $reserva->nombre_apellido !!}</td>
      <td>{!! $reserva->dni !!}</td>
      <td>
        @if($reserva->es_grupal)
          <span class="label label-info" style="font-size: 11px;"><i class="fa fa-users"></i> {!! $reserva->cantidad_personas !!} pers.</span>
        @else
          <span class="text-muted"><i class="fa fa-user"></i> 1</span>
        @endif
      </td>
      <td>{!! $reserva->nombre_institucion ?? '-' !!}</td>
      <td>
        @if ($reserva->turno_horario && $reserva->turno_horario->turno_tramite && $reserva->turno_horario->turno_tramite->tramite && $reserva->turno_horario->turno_tramite->tramite->dependencia)
          {!! $reserva->turno_horario->turno_tramite->tramite->dependencia->nombre !!}
        @endif
      </td>
      <td class="text-center">
        @if($reserva->estado_id == 1)
          <span class="label label-warning" style="font-size: 11px; padding: 4px 8px;"><i class="fa fa-clock-o"></i> Pendiente</span>
        @elseif($reserva->estado_id == 2)
          <span class="label label-default" style="font-size: 11px; padding: 4px 8px;"><i class="fa fa-check-circle"></i> Finalizado</span>
        @elseif($reserva->estado_id == 3)
          <span class="label label-success" style="font-size: 11px; padding: 4px 8px;"><i class="fa fa-check"></i> Confirmado</span>
        @elseif($reserva->estado_id == 4)
          <span class="label label-danger" style="font-size: 11px; padding: 4px 8px;"><i class="fa fa-times"></i> Cancelado</span>
        @else
          <span class="label label-info" style="font-size: 11px; padding: 4px 8px;">Registrado</span>
        @endif
      </td>
      <td class="text-center" style="white-space: nowrap;">
          <div style="display: flex; align-items: center; justify-content: center; gap: 4px;">
              <a href="{!! route('turnosdependenciasreservas.show', [$reserva->id]) !!}" class='btn btn-info btn-xs' title="Ver Detalle e Integrantes" style="margin: 0 2px;"><i class="fa fa-eye"></i></a>
              <a href="{!! route('turnosdependenciasreservas.edit', [$reserva->id]) !!}" class='btn btn-default btn-xs' title="Editar" style="margin: 0 2px;"><i class="fa fa-edit"></i></a>
              
              @if($reserva->estado_id != 3)
                {!! Form::open(['route' => ['turnosdependenciasreservas.cambiarEstado', $reserva->id], 'method' => 'post', 'style' => 'display:inline-block; margin:0 2px;']) !!}
                  <input type="hidden" name="estado_id" value="3">
                  <button type="submit" class="btn btn-success btn-xs" title="Aprobar / Confirmar Reserva"><i class="fa fa-check"></i></button>
                {!! Form::close() !!}
              @else
                {!! Form::open(['route' => ['turnosdependenciasreservas.cambiarEstado', $reserva->id], 'method' => 'post', 'style' => 'display:inline-block; margin:0 2px;']) !!}
                  <input type="hidden" name="estado_id" value="1">
                  <button type="submit" class="btn btn-warning btn-xs" title="Volver a Estado Pendiente"><i class="fa fa-undo"></i></button>
                {!! Form::close() !!}
              @endif

              <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal" data-id="{{ $reserva->id }}" data-url="{{ route('turnosdependenciasreservas.destroy', $reserva->id) }}" title="Eliminar" style="margin: 0 2px;"><i class="fa fa-trash"></i></a>
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