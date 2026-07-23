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

@section('script')
<script>
$(function () {
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