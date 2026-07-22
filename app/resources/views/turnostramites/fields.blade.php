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
    {!! Form::label('dependencia_tramite_id', 'Tramite:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('dependencia_tramite_id', ['' => 'SELECCIONAR'] + $dependenciaTramites, null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('fecha_desde', 'Fecha desde:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::date('fecha_desde', isset($turnostramites)? $turnostramites->fecha_desde->format('Y-m-d') : null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('fecha_hasta', 'Fecha hasta:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::date('fecha_hasta', isset($turnostramites)? $turnostramites->fecha_hasta->format('Y-m-d') : null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('tipo_evento_id', 'Tipo de evento:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('tipo_evento_id', ['' => 'SELECCIONAR'] + $tiposEvento, isset($turnostramites) ? $turnostramites->tipo_evento_id : null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('proyecto_extension_id', 'Proyecto de extensión:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('proyecto_extension_id', ['' => 'SELECCIONAR'] + $proyectosExtension, isset($turnostramites) ? $turnostramites->proyecto_extension_id : null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('responsable_id', 'Responsable:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('responsable_id', ['' => 'SELECCIONAR'] + $usuarios, isset($turnostramites) ? $turnostramites->responsable_id : null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('activo', 'Activo:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::hidden('activo', 0) !!}
        {!! Form::checkbox('activo', 1, isset($turnostramites) ? $turnostramites->activo : true, ['class' => 'flat']) !!}
    </div>
</div>

<div class="horarios-section">
    <h3>Horarios</h3>
    <div class="table-responsive">
        <table class="table" id="horarios-table">
            <thead>
                <tr>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Duración (min)</th>
                    <th>Turnos</th>
                    <th title="Lunes">L</th>
                    <th title="Martes">M</th>
                    <th title="Miércoles">X</th>
                    <th title="Jueves">J</th>
                    <th title="Viernes">V</th>
                    <th title="Sábado">S</th>
                    <th title="Domingo">D</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if(isset($turnostramites) && $turnostramites->turnosHorarios->count() > 0)
                    @foreach($turnostramites->turnosHorarios as $index => $horario)
                        <tr class="horario-row">
                            <td>
                                <input type="time" name="horarios[{{$index}}][hora_inicio]" 
                                    value="{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}" 
                                    class="form-control" {{ $horario->activo ? 'required' : '' }}>
                            </td>
                            <td>
                                <input type="time" name="horarios[{{$index}}][hora_fin]" 
                                    value="{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}" 
                                    class="form-control" {{ $horario->activo ? 'required' : '' }}>
                            </td>
                            <td>
                                <input type="number" 
                                       name="horarios[{{$index}}][duracion_minutos]" 
                                       value="{{ $horario->duracion_minutos }}" 
                                       class="form-control" 
                                       min="5" 
                                       max="60" 
                                       step="5" 
                                       {{ $horario->activo ? 'required' : '' }}>
                            </td>
                            <td>
                                <input type="number" 
                                       name="horarios[{{$index}}][cantidad_turnos]" 
                                       value="{{ $horario->cantidad_turnos ?? 1 }}" 
                                       class="form-control" 
                                       min="1" 
                                       {{ $horario->activo ? 'required' : '' }}>
                            </td>
                            <td>
                                <input type="hidden" name="horarios[{{$index}}][lunes]" value="0">
                                <input type="checkbox" name="horarios[{{$index}}][lunes]" value="1" {{ $horario->lunes ? 'checked' : '' }} class="flat">
                            </td>
                            <td>
                                <input type="hidden" name="horarios[{{$index}}][martes]" value="0">
                                <input type="checkbox" name="horarios[{{$index}}][martes]" value="1" {{ $horario->martes ? 'checked' : '' }} class="flat">
                            </td>
                            <td>
                                <input type="hidden" name="horarios[{{$index}}][miercoles]" value="0">
                                <input type="checkbox" name="horarios[{{$index}}][miercoles]" value="1" {{ $horario->miercoles ? 'checked' : '' }} class="flat">
                            </td>
                            <td>
                                <input type="hidden" name="horarios[{{$index}}][jueves]" value="0">
                                <input type="checkbox" name="horarios[{{$index}}][jueves]" value="1" {{ $horario->jueves ? 'checked' : '' }} class="flat">
                            </td>
                            <td>
                                <input type="hidden" name="horarios[{{$index}}][viernes]" value="0">
                                <input type="checkbox" name="horarios[{{$index}}][viernes]" value="1" {{ $horario->viernes ? 'checked' : '' }} class="flat">
                            </td>
                            <td>
                                <input type="hidden" name="horarios[{{$index}}][sabado]" value="0">
                                <input type="checkbox" name="horarios[{{$index}}][sabado]" value="1" {{ $horario->sabado ? 'checked' : '' }} class="flat">
                            </td>
                            <td>
                                <input type="hidden" name="horarios[{{$index}}][domingo]" value="0">
                                <input type="checkbox" name="horarios[{{$index}}][domingo]" value="1" {{ $horario->domingo ? 'checked' : '' }} class="flat">
                                <input type="hidden" name="horarios[{{$index}}][activo]" value="1">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs remove-horario">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="horario-row">
                        <td>
                            <input type="time" name="horarios[0][hora_inicio]" class="form-control" required>
                        </td>
                        <td>
                            <input type="time" name="horarios[0][hora_fin]" class="form-control" required>
                        </td>
                        <td>
                            <input type="number" 
                                   name="horarios[0][duracion_minutos]" 
                                   class="form-control" 
                                   min="5" 
                                   max="60" 
                                   step="5" 
                                   required>
                        </td>
                        <td>
                            <input type="number" 
                                   name="horarios[0][cantidad_turnos]" 
                                   class="form-control" 
                                   min="1" 
                                   value="1" 
                                   required>
                        </td>
                        <td>
                            <input type="hidden" name="horarios[0][lunes]" value="0">
                            <input type="checkbox" name="horarios[0][lunes]" value="1" checked class="flat">
                        </td>
                        <td>
                            <input type="hidden" name="horarios[0][martes]" value="0">
                            <input type="checkbox" name="horarios[0][martes]" value="1" checked class="flat">
                        </td>
                        <td>
                            <input type="hidden" name="horarios[0][miercoles]" value="0">
                            <input type="checkbox" name="horarios[0][miercoles]" value="1" checked class="flat">
                        </td>
                        <td>
                            <input type="hidden" name="horarios[0][jueves]" value="0">
                            <input type="checkbox" name="horarios[0][jueves]" value="1" checked class="flat">
                        </td>
                        <td>
                            <input type="hidden" name="horarios[0][viernes]" value="0">
                            <input type="checkbox" name="horarios[0][viernes]" value="1" checked class="flat">
                        </td>
                        <td>
                            <input type="hidden" name="horarios[0][sabado]" value="0">
                            <input type="checkbox" name="horarios[0][sabado]" value="1" checked class="flat">
                        </td>
                        <td>
                            <input type="hidden" name="horarios[0][domingo]" value="0">
                            <input type="checkbox" name="horarios[0][domingo]" value="1" checked class="flat">
                            <input type="hidden" name="horarios[0][activo]" value="1">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-xs remove-horario">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        <button type="button" class="btn btn-success btn-sm" id="add-horario">
            <i class="fa fa-plus"></i> Agregar Horario
        </button>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let horarioCount = {!! isset($turnostramites) ? $turnostramites->turnosHorarios->count() : 0 !!};
    

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
    

    document.querySelectorAll('.horario-row').forEach(row => {
        const checkbox = row.querySelector('input[type="checkbox"]');
        if (checkbox) {
            handleRequiredFields(checkbox, row);
        }
    });
    
    document.getElementById('add-horario').addEventListener('click', function() {
        horarioCount++;
        const template = document.querySelector('.horario-row').cloneNode(true);
        
        
        template.querySelectorAll('input').forEach(input => {
            const newName = input.name.replace(/horarios\[\d+\]/, `horarios[${horarioCount}]`);
            input.name = newName;
            
            if (input.type === 'checkbox') {
                input.checked = true;
            } else if (input.type === 'hidden') {
                input.value = '0';
            } else {
                input.value = '';
                input.setAttribute('required', 'required'); 
            }
        });
        
       
        template.querySelector('.remove-horario').addEventListener('click', function() {
            this.closest('tr').remove();
        });
        
        document.querySelector('#horarios-table tbody').appendChild(template);
        initializeICheck();
    });
    
    
    document.querySelectorAll('.remove-horario').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('tr').remove();
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
            }
        });

        if (activeHorariosCount === 0) {
            e.preventDefault();
            alert('Debe tener al menos un horario activo');
            return;
        }

        if (!valid) {
            e.preventDefault();
            alert('Complete todos los campos de los horarios activos');
            return;
        }
    });
});
</script>