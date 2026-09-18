@extends('layouts.panel-abm')

@section('title', 'INVESTIGACIÓN Y PUBLICACIONES')
@section('subtitle', 'Gestión de Artículos Científicos, Revistas y Publicaciones.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Listado de Investigaciones</h2>
            <div class="title_right">
              <a class="btn btn-primary pull-right" style="margin-bottom: 5px" href="{!! route('investigaciones.create') !!}">
                <i class="fa fa-plus"></i> Nueva Publicación
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
                        <th>Título</th>
                        <th>Revista / Cita</th>
                        <th style="width: 80px;" class="text-center">Año</th>
                        <th style="width: 120px;" class="text-center">Recurso</th>
                        <th style="width: 60px;" class="text-center">Orden</th>
                        <th style="width: 90px;" class="text-center">Estado</th>
                        <th style="width: 110px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($investigaciones as $inv)
                    <tr>
                        <td style="vertical-align: middle;">
                            <strong>{{ $inv->titulo }}</strong>
                            @if($inv->autores)
                                <br><small class="text-muted"><i class="fa fa-user"></i> {{ $inv->autores }}</small>
                            @endif
                        </td>
                        <td style="vertical-align: middle;">{{ $inv->revista ?? '—' }}</td>
                        <td class="text-center" style="vertical-align: middle;">
                            @if($inv->ano)
                                <span class="badge badge-info">{{ $inv->ano }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            @if($inv->pdf_url)
                                <a href="{{ $inv->pdf_url }}" target="_blank" class="btn btn-xs btn-danger" title="Descargar PDF">
                                    <i class="fa fa-file-pdf-o"></i> PDF
                                </a>
                            @endif
                            @if($inv->enlace_url)
                                <a href="{{ $inv->enlace_url }}" target="_blank" class="btn btn-xs btn-info" title="Enlace web">
                                    <i class="fa fa-external-link"></i> Web
                                </a>
                            @endif
                            @if(!$inv->pdf_url && !$inv->enlace_url)
                                <span class="text-muted small">Sin adjunto</span>
                            @endif
                        </td>
                        <td class="text-center" style="vertical-align: middle;">{{ $inv->orden }}</td>
                        <td class="text-center" style="vertical-align: middle;">
                            @if($inv->activo)
                                <span class="label label-success">Activo</span>
                            @else
                                <span class="label label-danger">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {!! Form::open(['route' => ['investigaciones.destroy', $inv->id], 'method' => 'delete']) !!}
                            <div class='btn-group'>
                                <a href="{!! route('investigaciones.edit', [$inv->id]) !!}" class='btn btn-default btn-xs' title="Editar"><i class="glyphicon glyphicon-edit"></i></a>
                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'title' => 'Eliminar', 'onclick' => "return confirm('¿Está seguro de eliminar esta publicación?')"]) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay publicaciones de investigación registradas. Haga clic en <strong>Nueva Publicación</strong> para agregar una.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            @if(isset($investigaciones))
                {{ $investigaciones->links() }}
            @endif
          </div>
        </div>
      </div>
    </div>
@stop
