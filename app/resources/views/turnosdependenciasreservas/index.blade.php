@extends('layouts.panel-abm')

@section('title', 'RESERVAS DE TRUNOS POR DEPENDENCIAS')
@section('subtitle', 'Administración de las reservas de turnos de dependencias.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
              {!! Form::open(['route' => 'turnosdependenciasreservas.index', 'method' => 'get']) !!}
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label for="inputEmail4">Código turno</label>
                    {!! Form::text('codigo_turno', (isset($codigo_turno)? $codigo_turno : null), ['class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Código turno']) !!}
                  </div>
                  <div class="form-group col-md-3">
                    <label for="inputEmail4">Fecha turno</label>
                    {!! Form::text('fecha_turno', (isset($fecha_turno)? $fecha_turno : null), ['class' => 'form-control', 'placeholder' => 'dd/mm/aaaa']) !!}
                  </div>
                  <div class="form-group col-md-3">
                    <label for="dependencia_id">Dependencia</label>
                    {!! Form::select('dependencia_id', $dependencias, (isset($dependencia_id)? $dependencia_id : null), ['class' => 'form-control', 'placeholder' => 'Seleccione una dependencia']) !!}
                  </div>
                  <div class="form-group col-md-3">
                    <label for="tramite_id">Trámite</label>
                    {!! Form::select('tramite_id', $tramites, (isset($tramite_id)? $tramite_id : null), ['class' => 'form-control', 'placeholder' => 'Seleccione un trámite']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    {{ Form::submit('Buscar', array('class' => 'btn btn-primary pull-right')) }}
              </form>
              {!! Form::open(['route' => 'turnosdependenciasreservas.print', 'method' => 'post']) !!}
                {!! Form::hidden('codigo_turno', (isset($codigo_turno)? $codigo_turno : null)) !!}
                {!! Form::hidden('fecha_turno', (isset($fecha_turno)? $fecha_turno : null)) !!}
                <button id="btn-delete-selected" type="button" class="btn btn-danger pull-right" style="display:none; margin-left: 5px;">Eliminar Seleccionados</button>
                <a href="{{ route('turnosdependenciasreservas.export') }}" class="btn btn-success pull-right"><i class="fa fa-file-excel-o"></i> Exportar</a>
                {{ Form::submit('Imprimir', array('class' => 'btn btn-secundary pull-right')) }}
              </form>
                  </div>
                </div>
              <br>
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

    // 1. Cuando se muestra el modal, guardar la URL en el propio botón de confirmación
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var url = button.data('url');
        $('#deleteConfirmBtn').data('url', url); 
    });

    // 2. Manejar el clic en el botón de confirmación de eliminación individual
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