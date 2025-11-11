<div class="container mt-4">
    <div class="row">
     
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('dependencia_id', 'Dependencia:') !!}
                <p>{!! $turnodependencia->dependencia->nombre !!}</p>
            </div>
            <div class="form-group">
                {!! Form::label('hora_desde', 'Hora desde:' ) !!}
                <p>{!! !is_null($turnodependencia->hora_desde)? \Carbon\Carbon::createFromFormat('H:i:s', $turnodependencia->hora_desde)->format('h:i') : null !!}</p>
            </div>
            <div class="form-group">
                {!! Form::label('fecha_desde', 'Fecha desde:' ) !!}
                <p>{!! $turnodependencia->fecha_desde->format('d/m/Y') !!}</p>
            </div>
        </div>

     
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('intervalo', 'Intervalo:' ) !!}
                <p>{!! $turnodependencia->intervalo !!}</p>
            </div>
            <div class="form-group">
                {!! Form::label('hora_hasta', 'Hora hasta:') !!}
                <p>{!! !is_null($turnodependencia->hora_hasta)? \Carbon\Carbon::createFromFormat('H:i:s', $turnodependencia->hora_hasta)->format('h:i') : null !!}</p>
            </div>
            <div class="form-group">
                {!! Form::label('fecha_hasta', 'Fecha hasta:') !!}
                <p>{!! $turnodependencia->fecha_hasta->format('d/m/Y') !!}</p>
            </div>
        </div>
    </div>

<div class="form-group">
    {!! Form::label('dias_semana', 'Días de la semana:') !!}
    <div style="display: flex; gap: 10px; margin-top: 5px;">
        <div>
            {!! Form::label('lunes', 'Lun:') !!}
            <p>{!! is_null($turnodependencia->lunes) ? null : '<i class="fa fa-check-square-o text-success" aria-hidden="true"></i>' !!}</p>
        </div>
        <div>
            {!! Form::label('martes', 'Mar:') !!}
            <p>{!! is_null($turnodependencia->martes) ? null : '<i class="fa fa-check-square-o text-success" aria-hidden="true"></i>' !!}</p>
        </div>
        <div>
            {!! Form::label('miercoles', 'Mié:') !!}
            <p>{!! is_null($turnodependencia->miercoles) ? null : '<i class="fa fa-check-square-o text-success" aria-hidden="true"></i>' !!}</p>
        </div>
        <div>
            {!! Form::label('jueves', 'Jue:') !!}
            <p>{!! is_null($turnodependencia->jueves) ? null : '<i class="fa fa-check-square-o text-success" aria-hidden="true"></i>' !!}</p>
        </div>
        <div>
            {!! Form::label('viernes', 'Vie:') !!}
            <p>{!! is_null($turnodependencia->viernes) ? null : '<i class="fa fa-check-square-o text-success" aria-hidden="true"></i>' !!}</p>
        </div>
    </div>
   </div>



</div>





