@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    {!! Form::label('dependencia_id', 'Dependencia / Área:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::select('dependencia_id', $dependencias, null, ['class' => 'form-control', 'required' => 'required']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('nombre', 'Nombre del Trámite:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('nombre', null, ['class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Ej: Visita Guiada Observatorio', 'required' => 'required']) !!}
  </div>
</div>

<div class="form-group">
  {!! Form::label('activo', 'Estado del Trámite:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    <label class="checkbox-inline">
      {!! Form::checkbox('activo', 1, (isset($tramitedependencia->id)?($tramitedependencia->activo??false):true)) !!} <strong>Habilitado</strong> (Visible para solicitar turnos)
    </label>
  </div>      
</div>

<hr style="margin: 25px 0 15px 0; border-top: 1px dashed #d1d5db;">

<!-- Selector Principal de Modalidad de Atención -->
<div class="form-group bg-light p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px;">
  {!! Form::label('tipo_modalidad', 'Modalidad de Reserva:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12', 'style' => 'font-size: 1.05rem; color: #1e293b;')) !!}
  <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::select('tipo_modalidad', [
      'individual' => '👤 Individual (Atención a 1 sola persona por turno)',
      'grupal' => '👥 Grupal (Grupos pequeños, familias o particulares)',
      'institucional' => '🏫 Institucional / Escuelas (Delegaciones, colegios o contingentes)',
      'mixto' => '⚙️ Mixto (Permite tanto turnos individuales como grupales/institucionales)'
    ], isset($tramitedependencia)? $tramitedependencia->tipo_modalidad : 'individual', ['class' => 'form-control input-lg', 'id' => 'tipo_modalidad', 'style' => 'font-weight: bold; height: 42px;']) !!}
    <span class="help-block"><small id="ayuda_modalidad" class="text-muted"></small></span>
  </div>
</div>

<!-- Sección Configuración de Grupos (Dinámica) -->
<div id="seccion_opciones_grupales" style="display: none; background: #fff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 20px; margin-top: 15px; margin-bottom: 20px;">
  <h5 style="margin-top: 0; margin-bottom: 15px; color: #0284c7; font-weight: bold;">
    <i class="fa fa-sliders"></i> Reglas para Reservas Grupales e Institucionales
  </h5>

  <div class="form-group" id="contenedor_max_personas">
    {!! Form::label('max_personas_reserva', 'Máximo de Asistentes por Turno:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
      {!! Form::number('max_personas_reserva', isset($tramitedependencia)? $tramitedependencia->max_personas_reserva : 30, ['class' => 'form-control', 'min' => 1, 'id' => 'max_personas_reserva']) !!}
      <span class="help-block"><small>Cantidad máxima de personas permitidas en un solo turno.</small></span>
    </div>
  </div>

  <div class="form-group" id="contenedor_min_personas">
    {!! Form::label('min_personas_reserva', 'Mínimo de Asistentes requeridos:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
      {!! Form::number('min_personas_reserva', isset($tramitedependencia)? $tramitedependencia->min_personas_reserva : 2, ['class' => 'form-control', 'min' => 1, 'id' => 'min_personas_reserva']) !!}
      <span class="help-block"><small>Mínimo de asistentes para habilitar la opción de grupo/escuela.</small></span>
    </div>
  </div>

  <div class="form-group" id="contenedor_requiere_institucion">
    {!! Form::label('requiere_institucion', 'Datos de la Institución:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
      <label class="checkbox-inline" style="font-weight: 600;">
        {!! Form::checkbox('requiere_institucion', 1, (isset($tramitedependencia)? ($tramitedependencia->requiere_institucion ?? false) : false), ['id' => 'requiere_institucion']) !!} 
        Solicitar Nombre de Escuela / Institución, Nivel Educativo y Docente a Cargo
      </label>
    </div>
  </div>

  <div class="form-group" id="contenedor_requiere_nomina">
    {!! Form::label('requiere_nomina', 'Nómina de Integrantes (Excel):', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
      <label class="checkbox-inline" style="font-weight: 600;">
        {!! Form::checkbox('requiere_nomina', 1, (isset($tramitedependencia)? ($tramitedependencia->requiere_nomina ?? false) : false), ['id' => 'requiere_nomina']) !!} 
        Exigir cargar la planilla Excel/CSV con la lista de personas (DNI y Nombres)
      </label>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    function actualizarModalidadUI() {
      var modalidad = $('#tipo_modalidad').val();
      var $seccionGrupales = $('#seccion_opciones_grupales');
      var $ayuda = $('#ayuda_modalidad');

      if (modalidad === 'individual') {
        $ayuda.text('El solicitante saca turno solo para 1 persona. No se pedirán datos grupales.');
        $seccionGrupales.slideUp(200);
      } else if (modalidad === 'grupal') {
        $ayuda.text('Permite seleccionar cantidad de personas (ej: familias, grupos particulares).');
        $seccionGrupales.slideDown(200);
        $('#contenedor_requiere_institucion').hide();
        $('#contenedor_requiere_nomina').show();
      } else if (modalidad === 'institucional') {
        $ayuda.text('Diseñado para escuelas, delegaciones y contingentes institucionales.');
        $seccionGrupales.slideDown(200);
        $('#contenedor_requiere_institucion').show();
        $('#contenedor_requiere_nomina').show();
      } else if (modalidad === 'mixto') {
        $ayuda.text('Permite al usuario optar por turno individual o declarar reserva grupal/institucional.');
        $seccionGrupales.slideDown(200);
        $('#contenedor_requiere_institucion').show();
        $('#contenedor_requiere_nomina').show();
      }
    }

    $('#tipo_modalidad').on('change', actualizarModalidadUI);
    actualizarModalidadUI(); // Ejecutar al cargar la página
  });
</script>