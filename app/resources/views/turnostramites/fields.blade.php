@if ($errors->any())
    <div class="alert alert-danger shadow-sm border-0 mb-4" style="border-radius: 8px;">
        <strong class="d-block mb-1"><i class="fa fa-exclamation-triangle"></i> Por favor verifique los siguientes errores:</strong>
        <ul class="mb-0 pl-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Panel Datos Generales -->
<div class="card mb-4" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <h4 style="margin-top: 0; margin-bottom: 18px; color: #1e293b; font-weight: bold; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
        <i class="fa fa-info-circle text-primary"></i> Información General del Trámite
    </h4>

    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group mb-3">
                {!! Form::label('dependencia_tramite_id', 'Trámite / Servicio *', ['class' => 'control-label font-weight-bold']) !!}
                {!! Form::select('dependencia_tramite_id', ['' => '--- Seleccionar Trámite ---'] + $dependenciaTramites, null, ['class' => 'form-control', 'required' => 'required', 'style' => 'height: 40px; border-radius: 6px;']) !!}
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="form-group mb-3">
                {!! Form::label('fecha_desde', 'Vigencia Desde *', ['class' => 'control-label font-weight-bold']) !!}
                {!! Form::date('fecha_desde', isset($turnostramites) ? $turnostramites->fecha_desde->format('Y-m-d') : \Carbon\Carbon::today()->format('Y-m-d'), ['class' => 'form-control', 'required' => 'required', 'style' => 'height: 40px; border-radius: 6px;']) !!}
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="form-group mb-3">
                {!! Form::label('fecha_hasta', 'Vigencia Hasta *', ['class' => 'control-label font-weight-bold']) !!}
                {!! Form::date('fecha_hasta', isset($turnostramites) ? $turnostramites->fecha_hasta->format('Y-m-d') : \Carbon\Carbon::today()->addMonths(3)->format('Y-m-d'), ['class' => 'form-control', 'required' => 'required', 'style' => 'height: 40px; border-radius: 6px;']) !!}
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-4 col-sm-12">
            <div class="form-group mb-3">
                {!! Form::label('tipo_evento_id', 'Tipo de Evento (Opcional)', ['class' => 'control-label']) !!}
                {!! Form::select('tipo_evento_id', ['' => '--- Ninguno ---'] + $tiposEvento, isset($turnostramites) ? $turnostramites->tipo_evento_id : null, ['class' => 'form-control', 'style' => 'height: 40px; border-radius: 6px;']) !!}
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="form-group mb-3">
                {!! Form::label('proyecto_extension_id', 'Proyecto de Extensión (Opcional)', ['class' => 'control-label']) !!}
                {!! Form::select('proyecto_extension_id', ['' => '--- Ninguno ---'] + $proyectosExtension, isset($turnostramites) ? $turnostramites->proyecto_extension_id : null, ['class' => 'form-control', 'style' => 'height: 40px; border-radius: 6px;']) !!}
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="form-group mb-3">
                {!! Form::label('responsable_id', 'Responsable A Cargo (Opcional)', ['class' => 'control-label']) !!}
                {!! Form::select('responsable_id', ['' => '--- Seleccionar Responsable ---'] + $usuarios, isset($turnostramites) ? $turnostramites->responsable_id : null, ['class' => 'form-control', 'style' => 'height: 40px; border-radius: 6px;']) !!}
            </div>
        </div>
    </div>

    <div class="form-group mt-2 mb-0">
        <label class="checkbox-inline font-weight-bold" style="font-size: 0.95rem; cursor: pointer;">
            {!! Form::hidden('activo', 0) !!}
            {!! Form::checkbox('activo', 1, isset($turnostramites) ? $turnostramites->activo : true, ['class' => 'flat']) !!}
            <span class="ml-1 text-success"><i class="fa fa-check-circle"></i> Configuración Activa</span> (Habilitada para generar turnos en el sistema)
        </label>
    </div>
</div>

<!-- Panel Grilla de Horarios -->
<div class="card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div class="d-flex justify-content-between align-items-center mb-3" style="border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
        <h4 style="margin: 0; color: #1e293b; font-weight: bold;">
            <i class="fa fa-clock-o text-primary"></i> Franjas Horarias y Días Habilitados
        </h4>
    </div>

    <div class="alert alert-info border-0 shadow-xs mb-3" style="background: #f0f9ff; color: #0369a1; border-radius: 8px; border-left: 4px solid #0284c7 !important;">
        <i class="fa fa-lightbulb-o"></i> <strong>Instrucciones de Subdivisión de Franjas:</strong>
        <ul class="mb-0 pl-3 mt-1" style="font-size: 0.9rem;">
            <li>Si define <strong>Hora Inicio: 08:00</strong>, <strong>Hora Fin: 12:00</strong> y <strong>Duración: 60 min</strong>, el sistema generará automáticamente 4 turnos (08:00, 09:00, 10:00 y 11:00 hs).</li>
            <li>Para trámites o visitas institucionales largas (ej: 2 horas / 120 min), ingrese la duración exacta en minutos.</li>
            <li><strong>Cupos por Turno</strong>: Representa la cantidad de turnos/recintos disponibles de forma simultánea en cada franja horario.</li>
            <li><strong>Sábados y Domingos</strong> vienen desmarcados por defecto. Márquelos únicamente si la atención incluye fines de semana.</li>
        </ul>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle" id="horarios-table" style="border-radius: 8px; overflow: hidden;">
            <thead style="background: #2A3F54; color: #ffffff;">
                <tr class="text-center">
                    <th style="width: 130px;">Hora Inicio</th>
                    <th style="width: 130px;">Hora Fin</th>
                    <th style="width: 120px;">Duración (min)</th>
                    <th style="width: 110px;">Cupos / Turnos</th>
                    <th title="Lunes" style="width: 45px; background: #34495E;">L</th>
                    <th title="Martes" style="width: 45px; background: #34495E;">M</th>
                    <th title="Miércoles" style="width: 45px; background: #34495E;">X</th>
                    <th title="Jueves" style="width: 45px; background: #34495E;">J</th>
                    <th title="Viernes" style="width: 45px; background: #34495E;">V</th>
                    <th title="Sábado" style="width: 45px; background: #d97706;">S</th>
                    <th title="Domingo" style="width: 45px; background: #d97706;">D</th>
                    <th style="width: 50px;">Acción</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($turnostramites) && $turnostramites->turnosHorarios->count() > 0)
                    @foreach($turnostramites->turnosHorarios as $index => $horario)
                        <tr class="horario-row text-center">
                            <td>
                                <input type="time" name="horarios[{{$index}}][hora_inicio]" 
                                    value="{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}" 
                                    class="form-control text-center input-hora-inicio" style="height: 38px; border-radius: 6px;" {{ $horario->activo ? 'required' : '' }}>
                            </td>
                            <td>
                                <input type="time" name="horarios[{{$index}}][hora_fin]" 
                                    value="{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}" 
                                    class="form-control text-center input-hora-fin" style="height: 38px; border-radius: 6px;" {{ $horario->activo ? 'required' : '' }}>
                            </td>
                            <td>
                                <input type="number" 
                                       name="horarios[{{$index}}][duracion_minutos]" 
                                       value="{{ $horario->duracion_minutos }}" 
                                       class="form-control text-center input-duracion" 
                                       min="5" 
                                       max="480" 
                                       step="5" 
                                       style="height: 38px; border-radius: 6px;"
                                       {{ $horario->activo ? 'required' : '' }}>
                            </td>
                            <td>
                                <input type="number" 
                                       name="horarios[{{$index}}][cantidad_turnos]" 
                                       value="{{ $horario->cantidad_turnos ?? 1 }}" 
                                       class="form-control text-center" 
                                       min="1" 
                                       style="height: 38px; border-radius: 6px;"
                                       {{ $horario->activo ? 'required' : '' }}>
                            </td>
                            <td class="align-middle">
                                <input type="hidden" name="horarios[{{$index}}][lunes]" value="0">
                                <input type="checkbox" name="horarios[{{$index}}][lunes]" value="1" {{ $horario->lunes ? 'checked' : '' }} class="flat">
                            </td>
                            <td class="align-middle">
                                <input type="hidden" name="horarios[{{$index}}][martes]" value="0">
                                <input type="checkbox" name="horarios[{{$index}}][martes]" value="1" {{ $horario->martes ? 'checked' : '' }} class="flat">
                            </td>
                            <td class="align-middle">
                                <input type="hidden" name="horarios[{{$index}}][miercoles]" value="0">
                                <input type="checkbox" name="horarios[{{$index}}][miercoles]" value="1" {{ $horario->miercoles ? 'checked' : '' }} class="flat">
                            </td>
                            <td class="align-middle">
                                <input type="hidden" name="horarios[{{$index}}][jueves]" value="0">
                                <input type="checkbox" name="horarios[{{$index}}][jueves]" value="1" {{ $horario->jueves ? 'checked' : '' }} class="flat">
                            </td>
                            <td class="align-middle">
                                <input type="hidden" name="horarios[{{$index}}][viernes]" value="0">
                                <input type="checkbox" name="horarios[{{$index}}][viernes]" value="1" {{ $horario->viernes ? 'checked' : '' }} class="flat">
                            </td>
                            <td class="align-middle" style="background: #fffbeb;">
                                <input type="hidden" name="horarios[{{$index}}][sabado]" value="0">
                                <input type="checkbox" name="horarios[{{$index}}][sabado]" value="1" {{ $horario->sabado ? 'checked' : '' }} class="flat">
                            </td>
                            <td class="align-middle" style="background: #fffbeb;">
                                <input type="hidden" name="horarios[{{$index}}][domingo]" value="0">
                                <input type="checkbox" name="horarios[{{$index}}][domingo]" value="1" {{ $horario->domingo ? 'checked' : '' }} class="flat">
                                <input type="hidden" name="horarios[{{$index}}][activo]" value="1">
                            </td>
                            <td class="align-middle">
                                <button type="button" class="btn btn-danger btn-xs remove-horario" title="Eliminar Franja">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="horario-feedback-row bg-light" style="font-size: 0.85rem;">
                            <td colspan="12" class="p-1 pl-3 text-left feedback-msg text-muted">
                                <!-- Cálculo dinámico JS -->
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="horario-row text-center">
                        <td>
                            <input type="time" name="horarios[0][hora_inicio]" class="form-control text-center input-hora-inicio" style="height: 38px; border-radius: 6px;" value="08:00" required>
                        </td>
                        <td>
                            <input type="time" name="horarios[0][hora_fin]" class="form-control text-center input-hora-fin" style="height: 38px; border-radius: 6px;" value="12:00" required>
                        </td>
                        <td>
                            <input type="number" 
                                   name="horarios[0][duracion_minutos]" 
                                   class="form-control text-center input-duracion" 
                                   min="5" 
                                   max="480" 
                                   step="5" 
                                   value="60"
                                   style="height: 38px; border-radius: 6px;"
                                   required>
                        </td>
                        <td>
                            <input type="number" 
                                   name="horarios[0][cantidad_turnos]" 
                                   class="form-control text-center" 
                                   min="1" 
                                   value="1" 
                                   style="height: 38px; border-radius: 6px;"
                                   required>
                        </td>
                        <td class="align-middle">
                            <input type="hidden" name="horarios[0][lunes]" value="0">
                            <input type="checkbox" name="horarios[0][lunes]" value="1" checked class="flat">
                        </td>
                        <td class="align-middle">
                            <input type="hidden" name="horarios[0][martes]" value="0">
                            <input type="checkbox" name="horarios[0][martes]" value="1" checked class="flat">
                        </td>
                        <td class="align-middle">
                            <input type="hidden" name="horarios[0][miercoles]" value="0">
                            <input type="checkbox" name="horarios[0][miercoles]" value="1" checked class="flat">
                        </td>
                        <td class="align-middle">
                            <input type="hidden" name="horarios[0][jueves]" value="0">
                            <input type="checkbox" name="horarios[0][jueves]" value="1" checked class="flat">
                        </td>
                        <td class="align-middle">
                            <input type="hidden" name="horarios[0][viernes]" value="0">
                            <input type="checkbox" name="horarios[0][viernes]" value="1" checked class="flat">
                        </td>
                        <td class="align-middle" style="background: #fffbeb;">
                            <input type="hidden" name="horarios[0][sabado]" value="0">
                            <input type="checkbox" name="horarios[0][sabado]" value="1" class="flat">
                        </td>
                        <td class="align-middle" style="background: #fffbeb;">
                            <input type="hidden" name="horarios[0][domingo]" value="0">
                            <input type="checkbox" name="horarios[0][domingo]" value="1" class="flat">
                            <input type="hidden" name="horarios[0][activo]" value="1">
                        </td>
                        <td class="align-middle">
                            <button type="button" class="btn btn-danger btn-xs remove-horario" title="Eliminar Franja">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="horario-feedback-row bg-light" style="font-size: 0.85rem;">
                        <td colspan="12" class="p-1 pl-3 text-left feedback-msg text-muted">
                            <!-- Cálculo dinámico JS -->
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="mt-2">
            <button type="button" class="btn btn-success font-weight-bold" id="add-horario" style="border-radius: 6px; padding: 8px 18px;">
                <i class="fa fa-plus-circle mr-1"></i> Agregar Nueva Franja Horaria
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let horarioCount = {!! isset($turnostramites) ? $turnostramites->turnosHorarios->count() : 0 !!};

    function calcularTurnosFila(row) {
        const hInicio = row.querySelector('.input-hora-inicio') ? row.querySelector('.input-hora-inicio').value : '';
        const hFin = row.querySelector('.input-hora-fin') ? row.querySelector('.input-hora-fin').value : '';
        const duracion = parseInt(row.querySelector('.input-duracion') ? row.querySelector('.input-duracion').value : 0);

        let feedbackRow = row.nextElementSibling;
        if (!feedbackRow || !feedbackRow.classList.contains('horario-feedback-row')) {
            return;
        }

        const feedbackTd = feedbackRow.querySelector('.feedback-msg');

        if (!hInicio || !hFin || !duracion || duracion <= 0) {
            feedbackTd.innerHTML = '<span class="text-muted"><i class="fa fa-info-circle"></i> Ingrese horario de inicio, fin y duración.</span>';
            return;
        }

        const [hI, mI] = hInicio.split(':').map(Number);
        const [hF, mF] = hFin.split(':').map(Number);

        const inicioMin = hI * 60 + mI;
        const finMin = hF * 60 + mF;

        if (finMin <= inicioMin) {
            feedbackTd.innerHTML = '<span class="text-danger font-weight-bold"><i class="fa fa-exclamation-circle"></i> Error: La hora de fin debe ser mayor que la hora de inicio.</span>';
            return;
        }

        const rangoMin = finMin - inicioMin;

        if (duracion > rangoMin) {
            feedbackTd.innerHTML = `<span class="text-danger font-weight-bold"><i class="fa fa-exclamation-circle"></i> Error: La duración (${duracion} min) es mayor que el tiempo total de la franja (${rangoMin} min).</span>`;
            return;
        }

        const cantidadSlots = Math.floor(rangoMin / duracion);
        const sobrante = rangoMin % duracion;

        let msg = `<span class="text-success font-weight-bold"><i class="fa fa-check-circle"></i> Se generarán <strong>${cantidadSlots} turnos</strong> de <strong>${duracion} min</strong> por cada día activo.</span>`;
        if (sobrante > 0) {
            msg += ` <span class="text-warning ml-2"><i class="fa fa-exclamation-triangle"></i> Quedan ${sobrante} minutos sobrantes al final de la franja.</span>`;
        }

        feedbackTd.innerHTML = msg;
    }

    function vincularValidacionFilas() {
        document.querySelectorAll('.horario-row').forEach(row => {
            const inputs = row.querySelectorAll('.input-hora-inicio, .input-hora-fin, .input-duracion');
            inputs.forEach(input => {
                input.removeEventListener('input', () => calcularTurnosFila(row));
                input.addEventListener('input', () => calcularTurnosFila(row));
            });
            calcularTurnosFila(row);
        });
    }

    function handleRequiredFields(checkbox, row) {
        const inputs = row.querySelectorAll('input[type="time"], input[type="number"]');
        inputs.forEach(input => {
            if (checkbox.checked) {
                input.setAttribute('required', '');
            } else {
                input.removeAttribute('required');
            }
        });
    }

    function initializeICheck() {
        if (typeof $.fn.iCheck !== 'undefined') {
            $('input.flat').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            }).on('ifChecked', function(event) {
                handleRequiredFields(this, this.closest('.horario-row'));
            }).on('ifUnchecked', function(event) {
                handleRequiredFields(this, this.closest('.horario-row'));
            });
        }
    }

    initializeICheck();
    vincularValidacionFilas();

    document.querySelectorAll('.horario-row').forEach(row => {
        const checkbox = row.querySelector('input[type="checkbox"]');
        if (checkbox) {
            handleRequiredFields(checkbox, row);
        }
    });

    document.getElementById('add-horario').addEventListener('click', function() {
        horarioCount++;
        const templateRow = document.querySelector('.horario-row').cloneNode(true);
        const templateFeedback = document.querySelector('.horario-feedback-row').cloneNode(true);

        templateRow.querySelectorAll('input').forEach(input => {
            const newName = input.name.replace(/horarios\[\d+\]/, `horarios[${horarioCount}]`);
            input.name = newName;

            if (input.type === 'checkbox') {
                if (input.name.includes('[sabado]') || input.name.includes('[domingo]')) {
                    input.checked = false;
                } else {
                    input.checked = true;
                }
            } else if (input.type === 'hidden') {
                if (input.name.includes('[activo]')) {
                    input.value = '1';
                } else {
                    input.value = '0';
                }
            } else {
                if (input.name.includes('[duracion_minutos]')) {
                    input.value = '60';
                } else if (input.name.includes('[cantidad_turnos]')) {
                    input.value = '1';
                } else {
                    input.value = '';
                }
                input.setAttribute('required', 'required');
            }
        });

        templateRow.querySelector('.remove-horario').addEventListener('click', function() {
            if (document.querySelectorAll('.horario-row').length > 1) {
                templateFeedback.remove();
                templateRow.remove();
            } else {
                alert('Debe conservar al menos una franja horaria.');
            }
        });

        document.querySelector('#horarios-table tbody').appendChild(templateRow);
        document.querySelector('#horarios-table tbody').appendChild(templateFeedback);

        initializeICheck();
        vincularValidacionFilas();
    });

    document.querySelectorAll('.remove-horario').forEach(button => {
        button.addEventListener('click', function() {
            if (document.querySelectorAll('.horario-row').length > 1) {
                const row = this.closest('.horario-row');
                const feedbackRow = row.nextElementSibling;
                if (feedbackRow && feedbackRow.classList.contains('horario-feedback-row')) {
                    feedbackRow.remove();
                }
                row.remove();
            } else {
                alert('Debe conservar al menos una franja horaria.');
            }
        });
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        let valid = true;
        let activeHorariosCount = 0;

        document.querySelectorAll('.horario-row').forEach(row => {
            const checkbox = row.querySelector('input[type="checkbox"]');
            if (checkbox.checked) {
                activeHorariosCount++;
                const inputs = row.querySelectorAll('input[type="time"], input[type="number"]');
                inputs.forEach(input => {
                    if (!input.value) {
                        valid = false;
                    }
                });

                const hInicio = row.querySelector('.input-hora-inicio').value;
                const hFin = row.querySelector('.input-hora-fin').value;
                const duracion = parseInt(row.querySelector('.input-duracion').value || 0);

                if (hInicio && hFin && duracion) {
                    const [hI, mI] = hInicio.split(':').map(Number);
                    const [hF, mF] = hFin.split(':').map(Number);
                    const inicioMin = hI * 60 + mI;
                    const finMin = hF * 60 + mF;
                    const rangoMin = finMin - inicioMin;

                    if (finMin <= inicioMin || duracion > rangoMin) {
                        valid = false;
                    }
                }
            }
        });

        if (activeHorariosCount === 0) {
            e.preventDefault();
            alert('Debe definir al menos un día habilitado en las franjas horarias.');
            return;
        }

        if (!valid) {
            e.preventDefault();
            alert('Por favor verifique que las horas de inicio, fin y duraciones sean coherentes.');
            return;
        }
    });
});
</script>