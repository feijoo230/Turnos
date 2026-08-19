@extends('layouts.panel-abm')

@section('title', 'RESERVAS DE TURNOS')
@section('subtitle', 'Administración y consulta de turnos reservados por las distintas dependencias')

@section('body')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2><i class="fa fa-calendar-check-o text-primary"></i> Filtros de Búsqueda de Reservas</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        {!! Form::open(['route' => 'turnosdependenciasreservas.index', 'method' => 'get', 'class' => 'form-horizontal']) !!}
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
              <label for="codigo_turno"><i class="fa fa-barcode"></i> Código de Turno</label>
              {!! Form::text('codigo_turno', (isset($codigo_turno)? $codigo_turno : null), ['class' => 'form-control', 'placeholder' => 'Ej: TUR-1234']) !!}
            </div>
            
            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
              <label for="fecha_turno"><i class="fa fa-calendar"></i> Fecha del Turno</label>
              {!! Form::text('fecha_turno', (isset($fecha_turno)? $fecha_turno : null), ['class' => 'form-control', 'placeholder' => 'DD/MM/AAAA']) !!}
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
              <label for="dependencia_id"><i class="fa fa-building-o"></i> Dependencia</label>
              {!! Form::select('dependencia_id', [null => '--- Todas las dependencias ---'] + (is_array($dependencias)? $dependencias : $dependencias->toArray()), (isset($dependencia_id)? $dependencia_id : null), ['class' => 'form-control']) !!}
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
              <label for="tramite_id"><i class="fa fa-list-alt"></i> Trámite</label>
              {!! Form::select('tramite_id', [null => '--- Todos los trámites ---'] + (is_array($tramites)? $tramites : $tramites->toArray()), (isset($tramite_id)? $tramite_id : null), ['class' => 'form-control']) !!}
            </div>
          </div>

          <div class="row" style="margin-top: 10px;">
            <div class="col-md-12 text-right">
              <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar Reservas</button>
              <a href="{{ route('turnosdependenciasreservas.index') }}" class="btn btn-default"><i class="fa fa-refresh"></i> Limpiar Filtros</a>
            </div>
          </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title d-flex justify-content-between align-items-center">
        <h2><i class="fa fa-list"></i> Listado de Reservas Encontradas</h2>
        <div class="title_right text-right">
          <div class="btn-group pull-right">
            <button id="btn-delete-selected" type="button" class="btn btn-danger" style="display:none; margin-right: 5px;">
              <i class="fa fa-trash"></i> Eliminar Seleccionados
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNuevaReservaManual" style="margin-right: 5px;">
              <i class="fa fa-plus-circle"></i> Nueva Reserva Manual
            </button>
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalCancelacionMasiva" style="margin-right: 5px;">
              <i class="fa fa-exclamation-triangle"></i> Cancelación Masiva
            </button>
            <a href="{{ route('turnosdependenciasreservas.export') }}" class="btn btn-success" style="margin-right: 5px;">
              <i class="fa fa-file-excel-o"></i> Exportar a Excel
            </a>
            {!! Form::open(['route' => 'turnosdependenciasreservas.print', 'method' => 'post', 'style' => 'display:inline;']) !!}
              {!! Form::hidden('codigo_turno', (isset($codigo_turno)? $codigo_turno : null)) !!}
              {!! Form::hidden('fecha_turno', (isset($fecha_turno)? $fecha_turno : null)) !!}
              <button type="submit" class="btn btn-default"><i class="fa fa-print"></i> Imprimir Listado</button>
            {!! Form::close() !!}
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        @include('turnosdependenciasreservas.table')
        <div class="text-center">{{ $reservas->appends(['codigo_turno' => $codigo_turno, 'fecha_turno' => $fecha_turno, 'dependencia_id' => $dependencia_id, 'tramite_id' => $tramite_id])->links() }}</div>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Cancelación Masiva -->
<div class="modal fade" id="modalCancelacionMasiva" tabindex="-1" role="dialog" aria-labelledby="modalCancelacionMasivaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title font-weight-bold" id="modalCancelacionMasivaLabel">
          <i class="fa fa-exclamation-triangle"></i> Cancelación Masiva de Turnos
        </h4>
      </div>
      {!! Form::open(['route' => 'turnosdependenciasreservas.cancelacionMasiva', 'method' => 'post']) !!}
        <div class="modal-body p-4">
          <div class="alert alert-warning" style="border-left: 4px solid #f0ad4e;">
            <i class="fa fa-info-circle"></i> Esta acción cancelará <strong>todos los turnos activos</strong> programados para la fecha seleccionada que coincidan con los filtros indicados y enviará una notificación a cada usuario afectado.
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label for="fecha_cancelacion" class="control-label"><i class="fa fa-calendar"></i> Fecha del Evento / Suspensión *</label>
              {!! Form::text('fecha_cancelacion', (isset($fecha_turno)? $fecha_turno : \Carbon\Carbon::today()->format('d/m/Y')), ['class' => 'form-control', 'placeholder' => 'DD/MM/AAAA', 'required' => 'required']) !!}
            </div>

            <div class="col-md-6 form-group">
              <label for="dependencia_id_masiva" class="control-label"><i class="fa fa-building-o"></i> Dependencia (Opcional)</label>
              {!! Form::select('dependencia_id', [null => '--- Todas las dependencias ---'] + (is_array($dependencias)? $dependencias : $dependencias->toArray()), (isset($dependencia_id)? $dependencia_id : null), ['class' => 'form-control', 'id' => 'dependencia_id_masiva']) !!}
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 form-group">
              <label for="tramite_id_masiva" class="control-label"><i class="fa fa-list-alt"></i> Trámite / Servicio (Opcional)</label>
              {!! Form::select('tramite_id', [null => '--- Todos los trámites ---'] + (is_array($tramites)? $tramites : $tramites->toArray()), (isset($tramite_id)? $tramite_id : null), ['class' => 'form-control', 'id' => 'tramite_id_masiva']) !!}
            </div>
          </div>

          <div class="form-group">
            <label for="motivo_cancelacion_masiva" class="control-label"><i class="fa fa-commenting"></i> Motivo de la Cancelación *</label>
            <textarea name="motivo_cancelacion" id="motivo_cancelacion_masiva" class="form-control" rows="3" placeholder="Ej: Suspensión de atención presencial por alerta meteorológico e inclemencias climáticas severas." required style="border-radius: 6px;"></textarea>
            <small class="text-muted">Este motivo se incluirá en el correo electrónico enviado a los ciudadanos afectados.</small>
          </div>

          <div class="checkbox">
            <label class="font-weight-bold text-dark">
              <input type="checkbox" name="notificar_email" value="1" checked> <i class="fa fa-envelope text-primary"></i> Enviar notificación por correo electrónico a los usuarios afectados
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-warning font-weight-bold" onclick="return confirm('¿Está absolutamente seguro de ejecutar la cancelación masiva para la fecha especificada?');">
            <i class="fa fa-check-circle"></i> Confirmar Cancelación Masiva
          </button>
        </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

<!-- Modal de Nueva Reserva Manual (Asignación por Admin) -->
<div class="modal fade" id="modalNuevaReservaManual" tabindex="-1" role="dialog" aria-labelledby="modalNuevaReservaManualLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title font-weight-bold" id="modalNuevaReservaManualLabel">
          <i class="fa fa-plus-circle"></i> Nueva Reserva Manual / Asignación por Administrador
        </h4>
      </div>
      {!! Form::open(['route' => 'turnosdependenciasreservas.storeManual', 'method' => 'post', 'id' => 'formNuevaReservaManual']) !!}
        <div class="modal-body p-4">
          <div class="alert alert-info" style="border-left: 4px solid #31708f;">
            <i class="fa fa-info-circle"></i> Utilice este formulario para registrar y confirmar reservas directamente para contingentes del interior, grupos institucionales o solicitudes presenciales.
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label for="tramite_id_manual" class="control-label"><i class="fa fa-list-alt"></i> Trámite / Dependencia *</label>
              {!! Form::select('dependencia_tramite_id', [null => '--- Seleccione un trámite ---'] + (is_array($tramites)? $tramites : $tramites->toArray()), null, ['class' => 'form-control', 'id' => 'tramite_id_manual', 'required' => 'required']) !!}
            </div>

            <div class="col-md-6 form-group">
              <label for="fecha_reserva_manual" class="control-label"><i class="fa fa-calendar"></i> Fecha de la Reserva *</label>
              {!! Form::text('fecha_reserva', \Carbon\Carbon::today()->format('d/m/Y'), ['class' => 'form-control', 'id' => 'fecha_reserva_manual', 'placeholder' => 'DD/MM/AAAA', 'required' => 'required']) !!}
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 form-group">
              <label for="turno_horario_id_manual" class="control-label"><i class="fa fa-clock-o"></i> Horario Disponible *</label>
              <select name="turno_horario_id" id="turno_horario_id_manual" class="form-control" required>
                <option value="">--- Seleccione primero trámite y fecha ---</option>
              </select>
            </div>
          </div>

          <hr style="margin: 15px 0;">
          <h4 class="text-primary font-weight-bold" style="font-size: 14px; margin-bottom: 15px;"><i class="fa fa-user"></i> Datos del Responsable / Solicitante</h4>

          <div class="row">
            <div class="col-md-6 form-group">
              <label for="nombre_apellido_manual" class="control-label">Apellido y Nombre *</label>
              <input type="text" name="nombre_apellido" id="nombre_apellido_manual" class="form-control" placeholder="Ej: Juan Pérez" required>
            </div>

            <div class="col-md-6 form-group">
              <label for="dni_manual" class="control-label">DNI *</label>
              <input type="text" name="dni" id="dni_manual" class="form-control" placeholder="Ej: 35123456" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label for="email_manual" class="control-label">Correo Electrónico (Opcional)</label>
              <input type="email" name="email" id="email_manual" class="form-control" placeholder="usuario@email.com">
            </div>

            <div class="col-md-6 form-group">
              <label for="celular_manual" class="control-label">Teléfono / Celular (Opcional)</label>
              <input type="text" name="celular" id="celular_manual" class="form-control" placeholder="Ej: 3874123456">
            </div>
          </div>

          <hr style="margin: 15px 0;">
          <h4 class="text-primary font-weight-bold" style="font-size: 14px; margin-bottom: 15px;"><i class="fa fa-users"></i> Datos de la Institución / Delegación</h4>

          <div class="row">
            <div class="col-md-6 form-group">
              <label for="nombre_institucion_manual" class="control-label">Nombre Institución / Grupo (Opcional)</label>
              <input type="text" name="nombre_institucion" id="nombre_institucion_manual" class="form-control" placeholder="Ej: Escuela 4001 / Delegación Metán">
            </div>

            <div class="col-md-3 form-group">
              <label for="cargo_responsable_manual" class="control-label">Cargo / Rol</label>
              <input type="text" name="cargo_responsable" id="cargo_responsable_manual" class="form-control" placeholder="Ej: Docente / Guía">
            </div>

            <div class="col-md-3 form-group">
              <label for="cantidad_personas_manual" class="control-label">Cantidad Asistentes *</label>
              <input type="number" name="cantidad_personas" id="cantidad_personas_manual" class="form-control" value="1" min="1" required>
            </div>
          </div>

          <div class="checkbox" style="margin-top: 10px;">
            <label class="font-weight-bold text-danger">
              <input type="checkbox" name="bloquear_cupo_completo" value="1"> <i class="fa fa-lock"></i> Bloquear / Reservar Cupo Completo del Horario (Asigna la totalidad de vacantes de ese horario)
            </label>
          </div>

          <div class="checkbox">
            <label class="font-weight-bold text-dark">
              <input type="checkbox" name="notificar_email" value="1" checked> <i class="fa fa-envelope text-primary"></i> Enviar notificación de confirmación por correo electrónico al responsable
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary font-weight-bold">
            <i class="fa fa-check-circle"></i> Crear y Confirmar Reserva Manual
          </button>
        </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

@section('script')
<script>
$(function () {
    @if(request()->has('abrir_cancelacion'))
        $('#modalCancelacionMasiva').modal('show');
    @endif

    // Cargar horarios dinámicamente en el modal de reserva manual
    function cargarHorariosAdmin() {
        var tramiteId = $('#tramite_id_manual').val();
        var fecha = $('#fecha_reserva_manual').val();
        var selectHorarios = $('#turno_horario_id_manual');

        if (!tramiteId || !fecha) {
            selectHorarios.html('<option value="">--- Seleccione primero trámite y fecha ---</option>');
            return;
        }

        selectHorarios.html('<option value="">Cargando horarios disponibles...</option>');

        $.ajax({
            url: '{{ route("turnosdependenciasreservas.loadHorariosAdmin") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                dependencia_tramite_id: tramiteId,
                fecha: fecha
            },
            success: function(response) {
                selectHorarios.empty();
                if (response.length === 0) {
                    selectHorarios.append('<option value="">No hay horarios configurados o activos para ese día de la semana</option>');
                } else {
                    selectHorarios.append('<option value="">--- Seleccione un horario ---</option>');
                    $.each(response, function(index, item) {
                        selectHorarios.append('<option value="' + item.id + '">' + item.label + '</option>');
                    });
                }
            },
            error: function() {
                selectHorarios.html('<option value="">Error al cargar horarios disponibles</option>');
            }
        });
    }

    $('#tramite_id_manual, #fecha_reserva_manual').on('change blur', cargarHorariosAdmin);
    $('#modalNuevaReservaManual').on('shown.bs.modal', cargarHorariosAdmin);
    // Manejar "Seleccionar todos"
    $('#select-all').on('click', function() {
        $('.select-item').prop('checked', this.checked);
        toggleDeleteButton();
    });

    // Manejar selección individual
    $(document).on('change', '.select-item', function() {
        if ($('.select-item:checked').length == $('.select-item').length) {
            $('#select-all').prop('checked', true);
        } else {
            $('#select-all').prop('checked', false);
        }
        toggleDeleteButton();
    });

    function toggleDeleteButton() {
        if ($('.select-item:checked').length > 0) {
            $('#btn-delete-selected').fadeIn();
        } else {
            $('#btn-delete-selected').fadeOut();
        }
    }

    // Ejecutar eliminación masiva
    $('#btn-delete-selected').on('click', function() {
        if (confirm('¿Está seguro de que desea eliminar las reservas seleccionadas?')) {
            var selectedIds = [];
            $('.select-item:checked').each(function() {
                selectedIds.push($(this).val());
            });

            $.ajax({
                url: '{{ route("turnosdependenciasreservas.massDestroy") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: selectedIds
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error al eliminar: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Error desconocido'));
                }
            });
        }
    });

    // Configuración global de AJAX para incluir el token CSRF en todas las peticiones
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Modal de confirmación de eliminación individual
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var url = button.data('url');
        $('#deleteConfirmBtn').data('url', url); 
    });

    $('#deleteConfirmBtn').on('click', function (e) {
        e.preventDefault(); 
        var deleteUrl = $(this).data('url');
        
        if (!deleteUrl) {
            alert("Error: No se encontró la URL para eliminar.");
            return;
        }

        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                response.json().then(data => {
                    var message = data.message || 'Error desconocido.';
                    alert('No se pudo eliminar: ' + message);
                });
            }
        })
        .catch(error => {
            alert('Ocurrió un error de red.');
        });
    });
});
</script>
@stop
@stop