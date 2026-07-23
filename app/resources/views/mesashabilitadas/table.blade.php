<table id="datatable" class="table table-striped table-bordered" style="width: 100%;">
    <thead>
        <tr>
            <th style="width: 40px;">#</th>
            <th>Mesa de Entrada / Dependencia</th>
            <th>Dependencia Superior (Padre)</th>
            <th>Tipo / Categoría</th>
            <th class="text-center" style="width: 130px;">Estado</th>
            <th class="text-center" style="width: 140px;">Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mesashabilitadas as $index => $mesahabilitada)
            @php
                $dep = $mesahabilitada->dependencia;
                $isActivo = $mesahabilitada->activo;
            @endphp
            <tr>
                <td><strong>{{ $index + 1 }}</strong></td>
                <td>
                    @if($dep)
                        @if($dep->es_unidad_academica)
                            <i class="fa fa-university text-primary mr-1"></i>
                        @else
                            <i class="fa fa-desktop text-success mr-1"></i>
                        @endif
                        <strong>{{ $dep->nombre }}</strong>
                        @if($dep->codigo)
                            <span class="label label-default" style="font-size: 10px; margin-left: 5px;">{{ $dep->codigo }}</span>
                        @endif
                    @else
                        <span class="text-danger"><i class="fa fa-exclamation-triangle"></i> Dependencia no encontrada</span>
                    @endif
                </td>
                <td>
                    @if($dep && $dep->parent)
                        <span class="text-primary"><i class="fa fa-level-up fa-rotate-90 text-muted mr-1"></i> {{ $dep->parent->nombre }}</span>
                    @elseif($dep && $dep->es_unidad_academica)
                        <span class="label label-primary"><i class="fa fa-star"></i> Sede / Unidad Principal</span>
                    @else
                        <span class="text-muted">&mdash;</span>
                    @endif
                </td>
                <td>
                    @if($dep && $dep->tipoDependencia)
                        {{ $dep->tipoDependencia->name }}
                    @else
                        <span class="text-muted">&mdash;</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($isActivo)
                        <span class="label label-success" style="font-size: 11px; padding: 4px 10px;">
                            <i class="fa fa-check-circle"></i> HABILITADA
                        </span>
                    @else
                        <span class="label label-default" style="font-size: 11px; padding: 4px 10px; background-color: #73879C;">
                            <i class="fa fa-pause-circle"></i> DESHABILITADA
                        </span>
                    @endif
                </td>
                <td class="text-center">
                    <div class="btn-group btn-group-xs">
                        {{-- Toggle Button --}}
                        <a href="{{ route('mesashabilitadas.toggle', [$mesahabilitada->id]) }}" 
                           class="btn {{ $isActivo ? 'btn-warning' : 'btn-success' }} btn-xs"
                           title="{{ $isActivo ? 'Deshabilitar Mesa' : 'Activar Mesa' }}">
                            <i class="fa {{ $isActivo ? 'fa-power-off' : 'fa-play' }}"></i>
                        </a>
                        
                        {{-- Edit --}}
                        <a href="{{ route('mesashabilitadas.edit', [$mesahabilitada->id]) }}" 
                           class="btn btn-default btn-xs" 
                           title="Editar">
                            <i class="fa fa-edit"></i>
                        </a>

                        {{-- Delete --}}
                        {!! Form::open(['route' => ['mesashabilitadas.destroy', $mesahabilitada->id], 'method' => 'delete', 'style' => 'display:inline; margin:0;']) !!}
                            {!! Form::button('<i class="fa fa-trash"></i>', [
                                'type' => 'submit', 
                                'class' => 'btn btn-danger btn-xs', 
                                'title' => 'Eliminar',
                                'onclick' => "return confirm('¿Está seguro de eliminar esta mesa habilitada?')"
                            ]) !!}
                        {!! Form::close() !!}
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>