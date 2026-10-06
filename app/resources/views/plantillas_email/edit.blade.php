@extends('layouts.panel-abm')

@section('title', 'MODIFICAR PLANTILLA DE CORREO')
@section('subtitle', 'Edición personalizada del asunto, estructura y contenido del correo electrónico.')

@section('body')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    
    <!-- Barra Superior de Navegación y Acciones -->
    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
      <div>
        <a href="{{ route('plantillas-email.index') }}" class="btn btn-default" style="border-radius: 6px; font-weight: 600;">
          <i class="fa fa-arrow-left"></i> Volver a Plantillas
        </a>
      </div>

      <div class="btn-group">
        <button type="button" class="btn btn-info" id="btnAbrirPreviewModal" style="border-radius: 6px 0 0 6px; font-weight: 600;">
          <i class="fa fa-eye"></i> Vista Previa en Pantalla Completa
        </button>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalPruebaEdit" style="border-radius: 0; font-weight: 600;">
          <i class="fa fa-paper-plane"></i> Enviar Correo de Prueba
        </button>
        <form method="POST" action="{{ route('plantillas-email.restablecer', $plantilla->id) }}" style="display: inline-block;" onsubmit="return confirm('¿Restablecer esta plantilla al diseño original de fábrica?');">
          {{ csrf_field() }}
          <button type="submit" class="btn btn-warning" style="border-radius: 0 6px 6px 0; font-weight: 600;">
            <i class="fa fa-undo"></i> Restablecer a Fábrica
          </button>
        </form>
      </div>
    </div>

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

    @if($errors->any())
      <div class="alert alert-danger" style="border-radius: 6px;">
        <ul style="margin: 0; padding-left: 20px;">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Tarjeta Principal de Edición -->
    <div class="x_panel" style="border-radius: 10px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); padding: 25px;">
      
      <!-- Ficha de Encabezado de la Plantilla -->
      <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px 22px; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div>
          <div style="margin-bottom: 6px;">
            @if($plantilla->circuito === 'colegio')
              <span class="badge" style="background-color: #4f46e5; color: #ffffff; padding: 5px 12px; font-size: 11px; font-weight: bold; border-radius: 6px;">
                <i class="fa fa-graduation-cap"></i> CIRCUITO COLEGIOS / DELEGACIONES
              </span>
            @else
              <span class="badge" style="background-color: #16a34a; color: #ffffff; padding: 5px 12px; font-size: 11px; font-weight: bold; border-radius: 6px;">
                <i class="fa fa-user"></i> CIRCUITO INDIVIDUAL
              </span>
            @endif

            <span class="badge" style="background-color: #0284c7; color: #ffffff; padding: 5px 10px; font-size: 11px; border-radius: 6px; margin-left: 4px;">
              Evento: {{ strtoupper($plantilla->evento) }}
            </span>
          </div>
          <h3 style="margin: 0 0 4px 0; font-size: 20px; font-weight: 700; color: #0f172a;">
            {{ $plantilla->nombre }}
          </h3>
          <span style="font-size: 13px; color: #64748b; font-family: monospace;">
            Identificador técnico: <strong>{{ $plantilla->clave }}</strong>
          </span>
        </div>

        <div>
          <span style="font-size: 12px; color: #94a3b8; display: block; text-align: right;">
            Estado de emisión:
          </span>
          <span class="label {{ $plantilla->activo ? 'label-success' : 'label-default' }}" style="font-size: 12px; padding: 5px 10px; border-radius: 4px; display: inline-block; margin-top: 4px;">
            {{ $plantilla->activo ? 'ACTIVA Y EN USO' : 'INACTIVA' }}
          </span>
        </div>
      </div>

      <form method="POST" action="{{ route('plantillas-email.update', $plantilla->id) }}" id="formEditPlantilla">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="row">
          <!-- Campo Asunto -->
          <div class="col-md-9 col-sm-8 col-xs-12">
            <div class="form-group" style="margin-bottom: 20px;">
              <label for="asunto" style="font-size: 14px; font-weight: 700; color: #1e293b;">
                Asunto del Correo Electrónico: <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control input-lg" id="asunto" name="asunto" value="{{ old('asunto', $plantilla->asunto) }}" required style="border-radius: 6px; font-weight: 600; font-size: 15px; border-color: #cbd5e1;">
              <span class="help-block" style="font-size: 12px; color: #64748b; margin-top: 4px;">
                <i class="fa fa-info-circle"></i> Puede insertar etiquetas como <code>{codigo}</code>, <code>{nombre}</code> o <code>{institucion}</code> que serán reemplazadas dinámicamente en el título del correo.
              </span>
            </div>
          </div>

          <!-- Switch Activo -->
          <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="form-group" style="margin-bottom: 20px; padding-top: 25px;">
              <label style="font-size: 14px; font-weight: 700; color: #1e293b; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="activo" value="1" {{ old('activo', $plantilla->activo) ? 'checked' : '' }} style="width: 20px; height: 20px;">
                <span>Plantilla Habilitada</span>
              </label>
              <small class="text-muted" style="display: block; font-size: 11px;">
                Si se desmarca, se utilizará la plantilla por defecto del sistema.
              </small>
            </div>
          </div>
        </div>

        <!-- Campo Descripción -->
        <div class="form-group" style="margin-bottom: 22px;">
          <label for="descripcion" style="font-size: 13px; font-weight: 600; color: #334155;">
            Descripción / Uso de la Plantilla:
          </label>
          <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{ old('descripcion', $plantilla->descripcion) }}" placeholder="Ej: Enviada al confirmar una reserva institucional para delegaciones..." style="border-radius: 6px;">
        </div>

        <!-- PANEL DE VARIABLES DINÁMICAS DISPONIBLES (TAG CLOUD INTERACTIVO) -->
        <div style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; padding: 16px 20px; margin-bottom: 25px;">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
            <strong style="color: #1e293b; font-size: 13px;">
              <i class="fa fa-tags text-primary"></i> Variables Disponibles (Haga clic en una etiqueta para insertarla en la posición del cursor):
            </strong>
            <span style="font-size: 11px; color: #64748b;">
              <i class="fa fa-mouse-pointer"></i> Clic = Insertar en el editor / Copiar
            </span>
          </div>

          <div style="display: flex; flex-wrap: wrap; gap: 6px;" id="contenedorVariables">
            @foreach($variables as $tag => $desc)
              <button type="button" class="btn btn-default btn-xs btn-variable-tag" 
                      data-tag="{{ $tag }}" 
                      data-toggle="tooltip" 
                      title="{{ $desc }}"
                      style="border-radius: 20px; padding: 4px 10px; font-family: monospace; font-size: 12px; font-weight: bold; background: #ffffff; border: 1px solid #94a3b8; color: #1e3c72; transition: all 0.15s ease;">
                <i class="fa fa-plus-circle" style="color: #16a34a; font-size: 10px; margin-right: 2px;"></i> {{ $tag }}
              </button>
            @endforeach
          </div>
        </div>

        <!-- PESTAÑAS: EDITOR HTML vs VISTA PREVIA EN VIVO -->
        <div class="" role="tabpanel" data-example-id="togglable-tabs" style="margin-bottom: 25px;">
          <ul id="editorTabs" class="nav nav-tabs bar_tabs" role="tablist">
            <li role="presentation" class="active">
              <a href="#tab_editor" id="editor-tab" role="tab" data-toggle="tab" aria-expanded="true" style="font-weight: 700;">
                <i class="fa fa-code text-primary"></i> Editor de Código HTML
              </a>
            </li>
            <li role="presentation" class="">
              <a href="#tab_preview" role="tab" id="preview-tab" data-toggle="tab" aria-expanded="false" style="font-weight: 700;">
                <i class="fa fa-eye text-success"></i> Vista Previa en Vivo (Datos Simulados)
              </a>
            </li>
          </ul>

          <div id="editorTabsContent" class="tab-content" style="border: 1px solid #ddd; border-top: none; padding: 15px; border-radius: 0 0 8px 8px; background: #ffffff;">
            
            <!-- Pestaña 1: Editor de Código -->
            <div role="tabpanel" class="tab-pane fade active in" id="tab_editor" aria-labelledby="editor-tab">
              <div style="background: #1e293b; color: #f8fafc; padding: 8px 14px; border-radius: 6px 6px 0 0; display: flex; align-items: center; justify-content: space-between; font-size: 12px;">
                <span>
                  <i class="fa fa-file-code-o"></i> Plantilla HTML (Estructura Responsive compatible con clientes de correo)
                </span>
                <div>
                  <button type="button" class="btn btn-default btn-xs" id="btnCopiarCodigo" style="font-size: 11px; padding: 2px 8px;">
                    <i class="fa fa-copy"></i> Copiar Código
                  </button>
                </div>
              </div>
              <textarea class="form-control" id="cuerpo_html" name="cuerpo_html" rows="22" required spellcheck="false" style="font-family: 'Consolas', 'Monaco', 'Courier New', monospace; font-size: 13px; line-height: 1.5; border-radius: 0 0 6px 6px; background: #0f172a; color: #e2e8f0; border-color: #1e293b; tab-size: 2;">{{ old('cuerpo_html', $plantilla->cuerpo_html) }}</textarea>
            </div>

            <!-- Pestaña 2: Vista Previa en Vivo -->
            <div role="tabpanel" class="tab-pane fade" id="tab_preview" aria-labelledby="preview-tab">
              <div style="background: #f8fafc; padding: 12px 18px; border-bottom: 1px solid #e2e8f0; font-size: 13px; color: #475569; display: flex; align-items: center; justify-content: space-between;">
                <span>
                  <i class="fa fa-magic text-success"></i> Vista previa generada al instante con las variables de simulación del circuito.
                </span>
                <span class="badge" style="background: #1e3c72; color: #ffffff;">
                  Asunto simulado: <span id="previewAsuntoTexto">{{ $dummyRender['asunto'] }}</span>
                </span>
              </div>
              <iframe id="iframeLivePreview" style="width: 100%; height: 580px; border: 1px solid #e2e8f0; border-radius: 0 0 6px 6px; background: #ffffff;"></iframe>
            </div>

          </div>
        </div>

        <!-- Botones de Acción Final -->
        <div style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 18px 0; margin-top: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
          <div>
            <a href="{{ route('plantillas-email.index') }}" class="btn btn-default" style="font-weight: 600;">
              Cancelar
            </a>
          </div>

          <div>
            <button type="submit" class="btn btn-primary btn-lg" style="font-weight: 700; padding: 10px 28px; border-radius: 6px; box-shadow: 0 4px 10px rgba(30,60,114,0.3);">
              <i class="fa fa-save"></i> Guardar Cambios en la Plantilla
            </button>
          </div>
        </div>

      </form>
    </div>

  </div>
</div>

<!-- MODAL VISTA PREVIA FULLSCREEN -->
<div class="modal fade" id="modalVistaPreviaFull" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 90%; max-width: 960px;" role="document">
    <div class="modal-content" style="border-radius: 10px; overflow: hidden;">
      <div class="modal-header" style="background: #1e3c72; color: #ffffff; padding: 14px 20px;">
        <button type="button" class="close" data-dismiss="modal" style="color: #ffffff; opacity: 0.8;">&times;</button>
        <h4 class="modal-title" style="font-weight: 700;">
          <i class="fa fa-eye"></i> Vista Previa de la Plantilla: {{ $plantilla->nombre }}
        </h4>
      </div>
      <div class="modal-body" style="padding: 0;">
        <iframe id="iframeModalFull" style="width: 100%; height: 620px; border: none; background: #ffffff;"></iframe>
      </div>
      <div class="modal-footer" style="background: #f8fafc;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL ENVIAR PRUEBA DESDE EDICIÓN -->
<div class="modal fade" id="modalPruebaEdit" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 10px; overflow: hidden;">
      <form method="POST" action="{{ route('plantillas-email.enviar-prueba', $plantilla->id) }}">
        {{ csrf_field() }}
        <div class="modal-header" style="background: #16a34a; color: #ffffff; padding: 14px 20px;">
          <button type="button" class="close" data-dismiss="modal" style="color: #ffffff; opacity: 0.8;">&times;</button>
          <h4 class="modal-title" style="font-weight: 700;">
            <i class="fa fa-paper-plane"></i> Enviar Correo de Prueba
          </h4>
        </div>
        <div class="modal-body" style="padding: 20px 24px;">
          <div class="alert alert-info" style="font-size: 13px; border-radius: 6px;">
            <i class="fa fa-info-circle"></i> Se despachará un correo a su casilla con el diseño guardado de <strong>{{ $plantilla->nombre }}</strong> y los datos de prueba del circuito {{ strtoupper($plantilla->circuito) }}.
          </div>
          <div class="form-group">
            <label for="email_prueba" style="font-weight: 700;">Correo Electrónico de Destino: <span class="text-danger">*</span></label>
            <input type="email" class="form-control input-lg" name="email_prueba" required value="{{ auth()->user()->email ?? '' }}" placeholder="tu_correo@unsa.edu.ar" style="border-radius: 6px;">
          </div>
        </div>
        <div class="modal-footer" style="background: #f8fafc;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success" style="font-weight: 700;">
            <i class="fa fa-send"></i> Enviar Prueba
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
  // Inicializar tooltips
  $('[data-toggle="tooltip"]').tooltip();

  var dummyData = @json($dummyData);
  var lastFocusedInput = $('#cuerpo_html');

  // Rastrear último elemento con foco para inserción de variables
  $('#asunto, #cuerpo_html').on('focus', function() {
    lastFocusedInput = $(this);
  });

  // Inserción interactiva de variables al hacer clic en un badge
  $(document).on('click', '.btn-variable-tag', function(e) {
    e.preventDefault();
    var tag = $(this).data('tag');
    var target = lastFocusedInput.length ? lastFocusedInput[0] : $('#cuerpo_html')[0];

    // Inserción en la posición del cursor
    if (target.setRangeText) {
      var start = target.selectionStart;
      var end = target.selectionEnd;
      target.setRangeText(tag, start, end, 'end');
      target.focus();
    } else {
      target.value += tag;
    }

    // Copiar al portapapeles como respaldo
    if (navigator.clipboard) {
      navigator.clipboard.writeText(tag);
    }

    // Animación de feedback
    var $btn = $(this);
    var originalBg = $btn.css('background-color');
    $btn.css('background-color', '#bbf7d0').css('border-color', '#16a34a');
    setTimeout(function() {
      $btn.css('background-color', originalBg).css('border-color', '#94a3b8');
    }, 400);

    // Actualizar vista previa si está activa
    actualizarVistaPrevia();
  });

  // Función para renderizar en vivo la vista previa sustituyendo variables
  function renderLiveHtml(htmlContent, subjectContent) {
    var renderedHtml = htmlContent || '';
    var renderedSubject = subjectContent || '';

    $.each(dummyData, function(key, val) {
      var placeholder = '{' + key + '}';
      renderedHtml = renderedHtml.split(placeholder).join(val);
      renderedSubject = renderedSubject.split(placeholder).join(val);
    });

    return {
      html: renderedHtml,
      subject: renderedSubject
    };
  }

  function setIframeContent(iframe, html) {
    if (!iframe) return;
    try {
      if ('srcdoc' in iframe) {
        iframe.srcdoc = html;
      } else if (iframe.contentWindow) {
        var doc = iframe.contentWindow.document;
        doc.open();
        doc.write(html);
        doc.close();
      }
    } catch (e) {
      if (iframe.contentWindow) {
        var doc = iframe.contentWindow.document;
        doc.open();
        doc.write(html);
        doc.close();
      }
    }
  }

  function actualizarVistaPrevia() {
    var rawHtml = $('#cuerpo_html').val();
    var rawSubject = $('#asunto').val();
    var res = renderLiveHtml(rawHtml, rawSubject);

    $('#previewAsuntoTexto').text(res.subject);

    var iframe = document.getElementById('iframeLivePreview');
    setIframeContent(iframe, res.html);
  }

  // Al cambiar a la pestaña de vista previa, renderizar
  $('#preview-tab').on('shown.bs.tab', function() {
    actualizarVistaPrevia();
  });

  // Modal Fullscreen
  $('#btnAbrirPreviewModal').on('click', function(e) {
    e.preventDefault();
    var rawHtml = $('#cuerpo_html').val();
    var rawSubject = $('#asunto').val();
    var res = renderLiveHtml(rawHtml, rawSubject);

    $('#modalVistaPreviaFull').modal('show');
    setTimeout(function() {
      var iframe = document.getElementById('iframeModalFull');
      setIframeContent(iframe, res.html);
    }, 150);
  });

  // Limpiar iframe al cerrar modal
  $('#modalVistaPreviaFull').on('hidden.bs.modal', function() {
    var iframe = document.getElementById('iframeModalFull');
    if (iframe) {
      setIframeContent(iframe, '');
    }
  });

  // Copiar código del textarea
  $('#btnCopiarCodigo').on('click', function() {
    var textarea = document.getElementById('cuerpo_html');
    textarea.select();
    document.execCommand('copy');
    alert('Código HTML copiado al portapapeles.');
  });

  // Render inicial para la pestaña de vista previa
  actualizarVistaPrevia();
});
</script>
@stop
