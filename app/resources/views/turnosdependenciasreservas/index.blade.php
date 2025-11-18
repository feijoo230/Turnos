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
    // Configuración global de AJAX para incluir el token CSRF en todas las peticiones
    // Esta es la forma estándar y recomendada en Laravel
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 1. Cuando se muestra el modal, guardar la URL en el propio botón de confirmación
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var url = button.data('url');
        
        console.log("URL para eliminar capturada:", url); 
        
        $('#deleteConfirmBtn').data('url', url); 
    });

    // 2. Manejar el clic en el botón de confirmación de eliminación
    $('#deleteConfirmBtn').on('click', function (e) {
        e.preventDefault(); 
        var deleteUrl = $(this).data('url');
        
        console.log("Enviando petición DELETE a:", deleteUrl);

        if (!deleteUrl) {
            alert("Error: No se encontró la URL para eliminar. Refresque la página y vuelva a intentarlo.");
            return;
        }

        // 3. Realizar la petición con la API nativa fetch
        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                // Si la respuesta es exitosa (ej. status 200-299)
                location.reload();
            } else {
                // Si hay un error, intentar leer el cuerpo del error como JSON
                response.json().then(data => {
                    console.error("Error del servidor:", data);
                    var message = data.message || 'Error desconocido del servidor.';
                    alert('No se pudo eliminar la reserva. Razón: ' + message);
                }).catch(() => {
                    // Si el cuerpo del error no es JSON
                    alert('No se pudo eliminar la reserva. Status: ' + response.statusText);
                });
            }
        })
        .catch(error => {
            console.error('Error en la petición fetch:', error);
            alert('Ocurrió un error de red. Revisa la consola.');
        });
    });
});
</script>
@stop
@stop