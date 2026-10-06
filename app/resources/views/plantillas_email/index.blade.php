@extends('layouts.panel-abm')

@section('title', 'GESTIÓN DE PLANTILLAS DE CORREO')
@section('subtitle', 'Administración y personalización de correos electrónicos para Circuitos Individuales y de Colegios / Delegaciones.')

@section('body')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">

    <!-- Tarjetas de Métricas -->
    <div class="row" style="margin-bottom: 20px;">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div style="background: #ffffff; border-radius: 8px; padding: 18px 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); border-left: 4px solid #1e3c72; display: flex; align-items: center; justify-content: space-between;">
          <div>
            <span style="font-size: 11px; text-transform: uppercase; font-weight: bold; color: #64748b; letter-spacing: 0.5px;">Total Plantillas</span>
            <h3 style="margin: 4px 0 0 0; font-weight: 700; color: #1e293b;">{{ $totalPlantillas }}</h3>
          </div>
          <div style="width: 44px; height: 44px; border-radius: 50%; background: #eff6ff; display: flex; align-items: center; justify-content: center;">
            <i class="fa fa-envelope-o fa-lg" style="color: #1e3c72;"></i>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div style="background: #ffffff; border-radius: 8px; padding: 18px 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); border-left: 4px solid #16a34a; display: flex; align-items: center; justify-content: space-between;">
          <div>
            <span style="font-size: 11px; text-transform: uppercase; font-weight: bold; color: #64748b; letter-spacing: 0.5px;">Circuito Individual</span>
            <h3 style="margin: 4px 0 0 0; font-weight: 700; color: #16a34a;">{{ $totalIndividual }}</h3>
          </div>
          <div style="width: 44px; height: 44px; border-radius: 50%; background: #f0fdf4; display: flex; align-items: center; justify-content: center;">
            <i class="fa fa-user fa-lg" style="color: #16a34a;"></i>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div style="background: #ffffff; border-radius: 8px; padding: 18px 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); border-left: 4px solid #4f46e5; display: flex; align-items: center; justify-content: space-between;">
          <div>
            <span style="font-size: 11px; text-transform: uppercase; font-weight: bold; color: #64748b; letter-spacing: 0.5px;">Circuito Colegios / Escuelas</span>
            <h3 style="margin: 4px 0 0 0; font-weight: 700; color: #4f46e5;">{{ $totalColegio }}</h3>
          </div>
          <div style="width: 44px; height: 44px; border-radius: 50%; background: #eef2ff; display: flex; align-items: center; justify-content: center;">
            <i class="fa fa-graduation-cap fa-lg" style="color: #4f46e5;"></i>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div style="background: #ffffff; border-radius: 8px; padding: 18px 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); border-left: 4px solid #0891b2; display: flex; align-items: center; justify-content: space-between;">
          <div>
            <span style="font-size: 11px; text-transform: uppercase; font-weight: bold; color: #64748b; letter-spacing: 0.5px;">Plantillas Activas</span>
            <h3 style="margin: 4px 0 0 0; font-weight: 700; color: #0891b2;">{{ $totalActivas }} / {{ $totalPlantillas }}</h3>
          </div>
          <div style="width: 44px; height: 44px; border-radius: 50%; background: #ecfeff; display: flex; align-items: center; justify-content: center;">
            <i class="fa fa-check-circle fa-lg" style="color: #0891b2;"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Panel Principal con Filtros y Tarjetas -->
    <div class="x_panel" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
      <div class="x_title" style="padding-bottom: 15px;">
        <div class="row align-items-center">
          <!-- Pestañas de Filtro por Circuito -->
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="btn-group" role="group" aria-label="Filtro Circuito">
              <a href="{{ route('plantillas-email.index', ['circuito' => 'todos', 'evento' => $evento]) }}" 
                 class="btn {{ ($circuito === 'todos' || empty($circuito)) ? 'btn-primary' : 'btn-default' }}"
                 style="font-weight: 600; font-size: 12px; border-radius: 6px 0 0 6px;">
                <i class="fa fa-th-large"></i> Todos ({{ $totalPlantillas }})
              </a>
              <a href="{{ route('plantillas-email.index', ['circuito' => 'individual', 'evento' => $evento]) }}" 
                 class="btn {{ $circuito === 'individual' ? 'btn-success' : 'btn-default' }}"
                 style="font-weight: 600; font-size: 12px;">
                <i class="fa fa-user"></i> 👤 Circuito Individual ({{ $totalIndividual }})
              </a>
              <a href="{{ route('plantillas-email.index', ['circuito' => 'colegio', 'evento' => $evento]) }}" 
                 class="btn {{ $circuito === 'colegio' ? 'btn-info' : 'btn-default' }}"
                 style="font-weight: 600; font-size: 12px; border-radius: 0 6px 6px 0;">
                <i class="fa fa-graduation-cap"></i> 🏫 Circuito Colegios ({{ $totalColegio }})
              </a>
            </div>
          </div>

          <!-- Filtro de Búsqueda y Evento -->
          <div class="col-md-6 col-sm-6 col-xs-12 text-right">
            <form method="GET" action="{{ route('plantillas-email.index') }}" class="form-inline pull-right">
              <input type="hidden" name="circuito" value="{{ $circuito }}">
              <div class="form-group" style="margin-right: 6px;">
                <select name="evento" class="form-control input-sm" onchange="this.form.submit()" style="border-radius: 4px;">
                  <option value="todos" {{ $evento === 'todos' ? 'selected' : '' }}>Todos los Eventos</option>
                  <option value="confirmacion" {{ $evento === 'confirmacion' ? 'selected' : '' }}>✓ Confirmación</option>
                  <option value="cancelacion" {{ $evento === 'cancelacion' ? 'selected' : '' }}>✕ Cancelación</option>
                  <option value="solicitud" {{ $evento === 'solicitud' ? 'selected' : '' }}>📋 Solicitud / Pendiente</option>
                </select>
              </div>
              <div class="input-group input-group-sm" style="margin-bottom: 0;">
                <input type="text" name="search" class="form-control" placeholder="Buscar plantilla..." value="{{ $search }}">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                  @if(!empty($search))
                    <a href="{{ route('plantillas-email.index', ['circuito' => $circuito, 'evento' => $evento]) }}" class="btn btn-danger" title="Limpiar filtro"><i class="fa fa-times"></i></a>
                  @endif
                </span>
              </div>
            </form>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>

      <div class="x_content" style="padding-top: 15px;">
        @include('parts.message')

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade in" role="alert" style="border-radius: 6px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <i class="fa fa-check-circle"></i> {{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade in" role="alert" style="border-radius: 6px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
          </div>
        @endif

        <div class="row">
          @forelse($plantillas as $tpl)
            <div class="col-md-6 col-sm-12 col-xs-12" style="margin-bottom: 24px;">
              <div class="panel" style="border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 3px 10px rgba(0,0,0,0.04); height: 100%; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden;">
                
                <!-- Encabezado de la Tarjeta con Badges -->
                <div style="background: #fafbfc; border-bottom: 1px solid #edf2f7; padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                  <div>
                    @if($tpl->circuito === 'colegio')
                      <span class="badge" style="background-color: #4f46e5; color: #ffffff; padding: 5px 10px; font-size: 11px; font-weight: bold; border-radius: 6px;">
                        <i class="fa fa-graduation-cap"></i> CIRCUITO COLEGIOS
                      </span>
                    @else
                      <span class="badge" style="background-color: #16a34a; color: #ffffff; padding: 5px 10px; font-size: 11px; font-weight: bold; border-radius: 6px;">
                        <i class="fa fa-user"></i> CIRCUITO INDIVIDUAL
                      </span>
                    @endif

                    @if($tpl->evento === 'confirmacion')
                      <span class="badge" style="background-color: #059669; color: #ffffff; padding: 5px 8px; font-size: 11px; border-radius: 6px;">
                        <i class="fa fa-check"></i> Confirmación
                      </span>
                    @elseif($tpl->evento === 'cancelacion')
                      <span class="badge" style="background-color: #dc2626; color: #ffffff; padding: 5px 8px; font-size: 11px; border-radius: 6px;">
                        <i class="fa fa-times"></i> Cancelación
                      </span>
                    @else
                      <span class="badge" style="background-color: #2563eb; color: #ffffff; padding: 5px 8px; font-size: 11px; border-radius: 6px;">
                        <i class="fa fa-clock-o"></i> Solicitud
                      </span>
                    @endif
                  </div>

                  <div>
                    @if($tpl->activo)
                      <span class="label label-success" style="font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                        <i class="fa fa-circle text-success" style="font-size: 8px;"></i> Activa
                      </span>
                    @else
                      <span class="label label-default" style="font-size: 11px; padding: 4px 8px; border-radius: 4px; background: #94a3b8;">
                        <i class="fa fa-circle-o" style="font-size: 8px;"></i> Inactiva
                      </span>
                    @endif
                  </div>
                </div>

                <!-- Cuerpo de la Tarjeta -->
                <div style="padding: 18px 20px; flex-grow: 1;">
                  <h4 style="margin: 0 0 6px 0; font-size: 16px; font-weight: 700; color: #1e293b;">
                    {{ $tpl->nombre }}
                  </h4>
                  <p style="margin: 0 0 12px 0; font-size: 12px; color: #64748b; font-family: monospace;">
                    Clave del sistema: <strong>{{ $tpl->clave }}</strong>
                  </p>

                  <p style="font-size: 13px; color: #475569; line-height: 1.5; margin-bottom: 14px;">
                    {{ $tpl->descripcion ?? 'Sin descripción configurada.' }}
                  </p>

                  <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 14px; margin-bottom: 10px;">
                    <small style="text-transform: uppercase; font-size: 10px; font-weight: bold; color: #94a3b8; display: block; margin-bottom: 2px;">
                      Asunto actual:
                    </small>
                    <span style="font-size: 13px; font-weight: 600; color: #1e293b; word-break: break-all;">
                      {{ $tpl->asunto }}
                    </span>
                  </div>

                  <small style="color: #94a3b8; font-size: 11px;">
                    <i class="fa fa-clock-o"></i> Última modificación: {{ $tpl->updated_at ? $tpl->updated_at->format('d/m/Y H:i') : 'Por defecto' }}
                  </small>
                </div>

                <!-- Botones de Acción -->
                <div style="background: #f8fafc; border-top: 1px solid #edf2f7; padding: 12px 18px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
                  <div class="btn-group">
                    <a href="{{ route('plantillas-email.edit', $tpl->id) }}" class="btn btn-primary btn-sm" style="font-weight: 600; border-radius: 4px;" title="Editar plantilla y variables">
                      <i class="fa fa-pencil"></i> Modificar Plantilla
                    </a>

                    <button type="button" class="btn btn-success btn-sm btn-enviar-prueba" 
                            data-id="{{ $tpl->id }}" 
                            data-title="{{ $tpl->nombre }}" 
                            data-circuito="{{ $tpl->circuito }}"
                            style="border-radius: 4px; margin-left: 4px;" 
                            title="Enviar un correo real a una casilla de prueba">
                      <i class="fa fa-paper-plane"></i> Enviar Prueba
                    </button>
                  </div>

                  <div class="pull-right">
                    <form method="POST" action="{{ route('plantillas-email.restablecer', $tpl->id) }}" style="display: inline-block;" onsubmit="return confirm('¿Está seguro de que desea restablecer esta plantilla a sus valores originales de fábrica? Se perderán las modificaciones personalizadas.');">
                      {{ csrf_field() }}
                      <button type="submit" class="btn btn-default btn-sm text-warning" title="Restablecer plantilla a fábrica" style="border-radius: 4px;">
                        <i class="fa fa-undo"></i>
                      </button>
                    </form>

                    <form method="POST" action="{{ route('plantillas-email.toggle', $tpl->id) }}" style="display: inline-block;">
                      {{ csrf_field() }}
                      <button type="submit" class="btn btn-default btn-sm {{ $tpl->activo ? 'text-danger' : 'text-success' }}" title="{{ $tpl->activo ? 'Desactivar plantilla' : 'Activar plantilla' }}" style="border-radius: 4px;">
                        <i class="fa {{ $tpl->activo ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                      </button>
                    </form>
                  </div>
                </div>

              </div>
            </div>
          @empty
            <div class="col-md-12 text-center" style="padding: 40px 20px;">
              <i class="fa fa-envelope-open-o fa-3x text-muted" style="margin-bottom: 12px;"></i>
              <h4 style="color: #64748b;">No se encontraron plantillas con los filtros seleccionados</h4>
              <a href="{{ route('plantillas-email.index') }}" class="btn btn-default btn-sm" style="margin-top: 10px;">
                <i class="fa fa-refresh"></i> Ver todas las plantillas
              </a>
            </div>
          @endforelse
        </div>

      </div>
    </div>
  </div>
</div>

<!-- MODAL ENVIAR PRUEBA -->
<div class="modal fade" id="modalEnviarPrueba" tabindex="-1" role="dialog" aria-labelledby="modalEnviarPruebaLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 10px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
      <form id="formEnviarPrueba" method="POST" action="">
        {{ csrf_field() }}
        <div class="modal-header" style="background: #16a34a; color: #ffffff; padding: 14px 20px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color: #ffffff; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalEnviarPruebaLabel" style="font-weight: 700; font-size: 16px;">
            <i class="fa fa-paper-plane"></i> Enviar Correo de Prueba
          </h4>
        </div>
        <div class="modal-body" style="padding: 20px 24px;">
          <div class="alert alert-info" style="border-radius: 6px; font-size: 13px;">
            <i class="fa fa-info-circle"></i> Se enviará un correo real utilizando la plantilla seleccionada (<strong id="modalPruebaNombre"></strong>), renderizada con los datos de simulación del circuito.
          </div>

          <div class="form-group">
            <label for="email_prueba" style="font-weight: 700; color: #1e293b;">
              Casilla de Correo de Destino: <span class="text-danger">*</span>
            </label>
            <input type="email" class="form-control input-lg" id="email_prueba" name="email_prueba" required placeholder="ej: tu_correo@unsa.edu.ar" value="{{ auth()->user()->email ?? '' }}" style="border-radius: 6px;">
            <span class="help-block" style="font-size: 12px; color: #64748b;">
              Ingrese la dirección donde desea recibir el correo para comprobar el formato, diseño y llegada.
            </span>
          </div>
        </div>
        <div class="modal-footer" style="padding: 14px 24px; background: #f8fafc;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success" style="font-weight: 700;">
            <i class="fa fa-send"></i> Enviar Correo Ahora
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@stop

@section('script')
<script>
$(document).ready(function() {
  // Manejo del modal de envío de prueba
  $(document).on('click', '.btn-enviar-prueba', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id') || $(this).data('id');
    var title = $(this).attr('data-title') || $(this).data('title');
    var formAction = "{{ url('plantillas-email') }}/" + id + "/enviar-prueba";
    
    $('#modalPruebaNombre').text(title);
    $('#formEnviarPrueba').attr('action', formAction);
    $('#modalEnviarPrueba').modal('show');
  });
});
</script>
@stop
