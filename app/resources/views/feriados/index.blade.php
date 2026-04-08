@extends('layouts.panel-abm')

@section('title', 'FERIADOS')
@section('subtitle', 'Feriados Nacionales.')
@section('body')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="input-group" style="margin-bottom: 0;">
                  <input type="text" class="form-control" placeholder="Ingrese su busqueda...">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-primary">Buscar</button>
                  </span>
                </div>
              </div>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <a class="btn btn-primary pull-right" style="margin-left: 5px;" href="{!! route('feriados.create') !!}">Nuevo</a>  
                <button id="btn-delete-selected" class="btn btn-danger pull-right" style="display:none; margin-left: 5px;">Eliminar Seleccionados</button>
                <a class="btn btn-info pull-right" style="margin-left: 5px;" href="{!! route('feriados.export') !!}">Exportar Excel</a>
                
                <div class="pull-right" style="margin-left: 10px;">
                  {!! Form::open(['route' => 'feriados.import', 'method' => 'POST', 'files' => true, 'class' => 'form-inline']) !!}
                    <div class="form-group" style="margin-bottom: 0;">
                      {!! Form::file('excel', ['class' => 'form-control', 'required' => 'required', 'style' => 'display:inline-block; width:180px; height: 34px; padding: 6px 12px;']) !!}
                      <button type="submit" class="btn btn-success" style="margin-bottom: 0;">Importar</button>
                    </div>
                  {!! Form::close() !!}
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @include('feriados.table')
          </div>
        </div>
      </div>
    </div>
@stop

@section('script')
<script>
    $(document).ready(function() {
        // Manejar "Seleccionar todos"
        $('#select-all').on('click', function() {
            $('.select-item').prop('checked', this.checked);
            toggleDeleteButton();
        });

        // Manejar selección individual
        $('.select-item').on('change', function() {
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
            if (confirm('¿Está seguro de que desea eliminar los elementos seleccionados?')) {
                var selectedIds = [];
                $('.select-item:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                $.ajax({
                    url: '{{ route("feriados.massDestroy") }}',
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
    });
</script>
@stop