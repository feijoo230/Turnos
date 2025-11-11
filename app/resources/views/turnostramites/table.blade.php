@if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
  </div>
@endif
<table id="datatable" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Dependencia</th>
      <th>Tramite</th>
      <th>Fecha desde</th>
      <th>Fecha hasta</th>
      <th>Activo</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
  @foreach($turnostramites as $turnostramite)
    <tr>
      <td>{!! $turnostramite->tramite->dependencia->nombre !!}</td>
      <td>{!! $turnostramite->tramite->nombre !!}</td>
      <td>{!! $turnostramite->fecha_desde->format('d/m/Y') !!}</td>
      <td>{!! $turnostramite->fecha_hasta->format('d/m/Y') !!}</td>
      <td>{!! $turnostramite->activo ? 'Si' : 'No' !!}</td>
      <td>
        {!! Form::open(['route' => ['turnostramites.destroy', $turnostramite->id], 'method' => 'delete']) !!}
          <div class='btn-group'>
            <button type="button" class="btn btn-xs btn-info show-horarios" data-turno="{{ $turnostramite->id }}">
              <i class="fa fa-clock-o"></i>
            </button>
            @if (count($turnostramite->reservas) > 0)
              <a class='btn btn-default btn-xs' disabled='true'><i class="fa fa-edit"></i></a>
              {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')", 'disabled' => 'disabled']) !!}
            @else
              <a href="{!! route('turnostramites.edit', [$turnostramite->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
              {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')"]) !!}
            @endif
          </div>
        {!! Form::close() !!}
      </td>
    </tr>
    <tr class="horarios-row" id="horarios-{{ $turnostramite->id }}" style="display: none;">
      <td colspan="7">
        <table class="table table-condensed">
          <thead>
            <tr>
              <th>Hora Inicio</th>
              <th>Hora Fin</th>
              <th>Duración (min)</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            @forelse($turnostramite->turnosHorarios as $horario)
              <tr>
                <td>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</td>
                <td>{{ $horario->duracion_minutos }}</td>
                <td>{{ $horario->activo ? 'Activo' : 'Inactivo' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center">No hay horarios definidos</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.show-horarios');
    
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const turnoId = this.dataset.turno;
            const horariosRow = document.getElementById(`horarios-${turnoId}`);
            
            if (horariosRow.style.display === 'none') {
                horariosRow.style.display = 'table-row';
                this.innerHTML = '<i class="fa fa-clock-o fa-spin"></i>';
            } else {
                horariosRow.style.display = 'none';
                this.innerHTML = '<i class="fa fa-clock-o"></i>';
            }
        });
    });
});
</script>