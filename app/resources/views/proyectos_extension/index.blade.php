@extends('layouts.panel-abm')

@section('title', 'PROYECTOS DE EXTENSIÓN')
@section('subtitle', 'Gestión de Proyectos de Extensión Universitarios.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Listado de Proyectos</h2>
            <div class="title_right">
              <a class="btn btn-primary pull-right" style="margin-bottom: 5px" href="{!! route('proyectos-extension.create') !!}">
                <i class="fa fa-plus"></i> Nuevo Proyecto
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
                        <th style="width: 70px;">Portada</th>
                        <th>Nombre / Título</th>
                        <th>Eje Temático</th>
                        <th style="width: 80px;" class="text-center">Año</th>
                        <th style="width: 60px;" class="text-center">Orden</th>
                        <th style="width: 90px;" class="text-center">Estado</th>
                        <th style="width: 110px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($proyectos as $proyecto)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            <img src="{{ $proyecto->imagen_url }}" alt="Portada" style="width: 50px; height: 35px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                        </td>
                        <td style="vertical-align: middle;">
                            <strong>{{ $proyecto->nombre }}</strong>
                            @if($proyecto->enlace_url)
                                <br><a href="{{ $proyecto->enlace_url }}" target="_blank" class="small text-info"><i class="fa fa-external-link"></i> Ver enlace</a>
                            @endif
                        </td>
                        <td style="vertical-align: middle;">{{ $proyecto->subtitulo ?? '—' }}</td>
                        <td class="text-center" style="vertical-align: middle;">
                            @if($proyecto->ano)
                                <span class="badge badge-info">{{ $proyecto->ano }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center" style="vertical-align: middle;">{{ $proyecto->orden }}</td>
                        <td class="text-center" style="vertical-align: middle;">
                            @if($proyecto->activo)
                                <span class="label label-success">Activo</span>
                            @else
                                <span class="label label-danger">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {!! Form::open(['route' => ['proyectos-extension.destroy', $proyecto->id], 'method' => 'delete']) !!}
                            <div class='btn-group'>
                                <a href="{!! route('proyectos-extension.edit', [$proyecto->id]) !!}" class='btn btn-default btn-xs' title="Editar"><i class="glyphicon glyphicon-edit"></i></a>
                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'title' => 'Eliminar', 'onclick' => "return confirm('¿Está seguro de eliminar este proyecto?')"]) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay proyectos de extensión registrados. Haga clic en <strong>Nuevo Proyecto</strong> para agregar uno.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            @if(isset($proyectos))
                {{ $proyectos->links() }}
            @endif
          </div>
        </div>
      </div>
    </div>
@stop
