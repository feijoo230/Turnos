<table id="datatable" class="table table-striped table-bordered" style="width: 100%;">
  <thead>
    <tr>
      <th style="width: 40px;">#</th>
      <th>Nombre del Trámite</th>
      <th>Dependencia / Área</th>
      <th class="text-center">Modalidad / Capacidad</th>
      <th class="text-center" style="width: 110px;">Estado</th>
      <th class="text-center" style="width: 120px;">Acción</th>
    </tr>
  </thead>
  <tbody>
  @foreach($tramitesdependencia as $index => $tramitedependencia)
    <tr>
      <td><strong>{{ $index + 1 }}</strong></td>
      <td>
        <i class="fa fa-list-alt text-primary mr-1"></i>
        <strong>{!! $tramitedependencia->nombre !!}</strong>
      </td>
      <td>
        @if($tramitedependencia->dependencia)
          <i class="fa fa-building-o text-muted mr-1"></i>
          {!! $tramitedependencia->dependencia->string_path ?? $tramitedependencia->dependencia->nombre !!}
        @else
          <span class="text-muted">&mdash;</span>
        @endif
      </td>
      <td class="text-center">
        @if($tramitedependencia->permite_grupal || $tramitedependencia->tipo_modalidad != 'individual')
          <span class="label label-info"><i class="fa fa-users"></i> {{ ucfirst($tramitedependencia->tipo_modalidad ?? 'Grupal') }}</span>
          <br><small class="text-muted">Máx: {{ $tramitedependencia->max_personas_reserva ?? 10 }} pers.</small>
        @else
          <span class="label label-default"><i class="fa fa-user"></i> Individual</span>
        @endif
      </td>
      <td class="text-center">
        @if ($tramitedependencia->activo == TRUE)
          <span class="label label-success" style="font-size: 11px; padding: 3px 8px;"><i class="fa fa-check"></i> Activo</span>
        @else
          <span class="label label-default" style="font-size: 11px; padding: 3px 8px; background-color: #73879C;"><i class="fa fa-pause"></i> Inactivo</span>
        @endif
      </td>
      <td class="text-center">
        {!! Form::open(['route' => ['tramitesdependencias.destroy', $tramitedependencia->id], 'method' => 'delete', 'style' => 'margin: 0px; display:inline;']) !!}
          <div class='btn-group btn-group-xs'>
              <a href="{!! route('tramitesdependencias.edit', [$tramitedependencia->id]) !!}" class='btn btn-default btn-xs' title="Editar"><i class="fa fa-edit"></i></a>
              {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'title' => 'Eliminar', 'onclick' => "return confirm('¿Esta seguro de eliminar este trámite?')"]) !!}
          </div>
        {!! Form::close() !!}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>