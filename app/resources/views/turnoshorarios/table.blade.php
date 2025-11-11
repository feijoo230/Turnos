@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<table id="datatable" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Tramites</th>
            <th>Horario</th>
            <th>Fecha desde</th>
            <th>Fecha hasta</th>
            <th>Activo</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
    @foreach($turnosHorarios as $turnohorario)
        <tr>
            <td>{!! $turnohorario->turnoTramite->tramite->dependencia->nombre - $turnohorario->turnoTramite->tramite->nombre!!}</td>
            <td>{!! $turnohorario->horario !!}</td>
            <td>{!! $turnohorario->fecha_desde->format('d/m/Y') !!}</td>
            <td>{!! $turnohorario->fecha_hasta->format('d/m/Y') !!}</td>
            <td>{!! $turnohorario->activo ? 'Si' : 'No' !!}</td>
            <td>
                {!! Form::open(['route' => ['turnoshorarios.destroy', $turnohorario->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                                <a href="{!! route('turnoshorarios.show', [$turnohorario->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-clock-o"></i></a>
                            @if (count($turnohorario->reservas) > 0)
                                <a class='btn btn-default btn-xs' disabled='true'><i class="fa fa-edit"></i></a>
                                {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')", 'disabled' => 'disabled']) !!}
                            @else
                                <a href="{!! route('turnoshorarios.edit', [$turnohorario->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                                {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')"]) !!}
                            @endif
                    </div>
                {!! Form::close() !!}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
