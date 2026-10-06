@extends('layouts.panel-abm')

@section('title', 'INSTALACIONES Y EQUIPAMIENTO')
@section('subtitle', 'Gestión de Espacios Físicos, Telescopios e Instrumental del Observatorio.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Listado de Instalaciones y Equipamiento</h2>
            <div class="title_right">
              <a class="btn btn-primary pull-right" style="margin-bottom: 5px" href="{!! route('instalaciones-gestion.create') !!}">
                <i class="fa fa-plus"></i> Nueva Instalación / Equipamiento
              </a>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    {{ session('error') }}
                </div>
            @endif

            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th style="width: 80px;" class="text-center">Imagen</th>
                        <th>Nombre y Detalle</th>
                        <th>Características / Puntos Clave</th>
                        <th style="width: 70px;" class="text-center">Orden</th>
                        <th style="width: 90px;" class="text-center">Estado</th>
                        <th style="width: 110px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($instalaciones as $inst)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            @if($inst->imagen_url)
                                <img src="{{ $inst->imagen_url }}" alt="{{ $inst->nombre }}" style="width: 70px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                            @else
                                <span class="badge" style="background-color: #f1f5f9; color: #94a3b8; padding: 12px; font-size: 16px;">
                                    <i class="fa fa-picture-o"></i>
                                </span>
                            @endif
                        </td>
                        <td style="vertical-align: middle;">
                            <strong style="font-size: 15px;">
                                @if($inst->icono)
                                    <i class="{{ $inst->icono }} text-primary mr-1"></i>
                                @endif
                                {{ $inst->nombre }}
                            </strong>
                            <p class="text-muted" style="margin: 5px 0 0; font-size: 13px;">{{ \Illuminate\Support\Str::limit($inst->descripcion, 160) }}</p>
                        </td>
                        <td style="vertical-align: middle;">
                            @if(count($inst->caracteristicas_list) > 0)
                                <ul style="margin-bottom: 0; padding-left: 18px; font-size: 12px; color: #475569;">
                                    @foreach(array_slice($inst->caracteristicas_list, 0, 3) as $c)
                                        <li>{{ $c }}</li>
                                    @endforeach
                                    @if(count($inst->caracteristicas_list) > 3)
                                        <li class="text-muted"><em>+{{ count($inst->caracteristicas_list) - 3 }} más...</em></li>
                                    @endif
                                </ul>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="text-center" style="vertical-align: middle;">{{ $inst->orden }}</td>
                        <td class="text-center" style="vertical-align: middle;">
                            @if($inst->activo)
                                <span class="label label-success">Activo</span>
                            @else
                                <span class="label label-danger">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {!! Form::open(['route' => ['instalaciones-gestion.destroy', $inst->id], 'method' => 'delete']) !!}
                            <div class='btn-group'>
                                <a href="{!! route('instalaciones-gestion.edit', [$inst->id]) !!}" class='btn btn-default btn-xs' title="Editar"><i class="glyphicon glyphicon-edit"></i></a>
                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'title' => 'Eliminar', 'onclick' => "return confirm('¿Está seguro de eliminar esta instalación/equipamiento?')"]) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay instalaciones o equipamiento registrados. Haga clic en <strong>Nueva Instalación</strong> para agregar.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            @if(isset($instalaciones))
                <div class="text-center">
                    {{ $instalaciones->links() }}
                </div>
            @endif
          </div>
        </div>
      </div>
    </div>
@endsection
