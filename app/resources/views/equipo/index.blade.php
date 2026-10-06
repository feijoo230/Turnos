@extends('layouts.panel-abm')

@section('title', 'EQUIPO DE TRABAJO')
@section('subtitle', 'Gestión de Integrantes, Docentes Responsables y Colaboradores del Observatorio.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Listado de Integrantes</h2>
            <div class="title_right">
              <a class="btn btn-primary pull-right" style="margin-bottom: 5px" href="{!! route('equipo-trabajo.create') !!}">
                <i class="fa fa-user-plus"></i> Nuevo Integrante
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

            <!-- Filtros por Tipo -->
            <div class="well well-sm" style="background-color: #f8fafc; border: 1px solid #e2e8f0; margin-bottom: 15px;">
                <form method="GET" action="{{ route('equipo-trabajo.index') }}" class="form-inline">
                    <label style="margin-right: 10px; font-weight: 600;"><i class="fa fa-filter"></i> Filtrar por Tipo:</label>
                    <select name="tipo" class="form-control input-sm" onchange="this.form.submit()">
                        <option value="todos" {{ $tipo == 'todos' ? 'selected' : '' }}>Todos los Integrantes</option>
                        <option value="responsable" {{ $tipo == 'responsable' ? 'selected' : '' }}>Docentes Responsables</option>
                        <option value="colaborador" {{ $tipo == 'colaborador' ? 'selected' : '' }}>Colaboradores</option>
                    </select>
                    @if($tipo != 'todos')
                        <a href="{{ route('equipo-trabajo.index') }}" class="btn btn-default btn-sm" style="margin-left: 8px;">
                            <i class="fa fa-times"></i> Quitar Filtro
                        </a>
                    @endif
                </form>
            </div>

            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;" class="text-center">Foto</th>
                        <th>Nombre y Apellido</th>
                        <th style="width: 170px;" class="text-center">Tipo de Integrante</th>
                        <th>Cargo / Rol</th>
                        <th>Usuario Vinculado</th>
                        <th style="width: 70px;" class="text-center">Orden</th>
                        <th style="width: 90px;" class="text-center">Estado</th>
                        <th style="width: 110px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($miembros as $m)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            @if($m->foto_url)
                                <img src="{{ $m->foto_url }}" alt="{{ $m->nombre }}" class="img-circle" style="width: 42px; height: 42px; object-fit: cover; border: 1px solid #ddd;">
                            @else
                                <span class="badge" style="background-color: #e2e8f0; color: #64748b; font-size: 16px; padding: 8px 10px; border-radius: 50%;">
                                    <i class="fa fa-user"></i>
                                </span>
                            @endif
                        </td>
                        <td style="vertical-align: middle;">
                            <strong>{{ $m->nombre }}</strong>
                            @if($m->email)
                                <br><small class="text-muted"><i class="fa fa-envelope-o"></i> {{ $m->email }}</small>
                            @endif
                            @if($m->area)
                                <br><small class="text-info"><i class="fa fa-tag"></i> {{ $m->area }}</small>
                            @endif
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            @if($m->tipo == 'responsable')
                                <span class="label label-primary" style="font-size: 11px;"><i class="fa fa-star"></i> Docente Responsable</span>
                            @else
                                <span class="label label-info" style="font-size: 11px;"><i class="fa fa-users"></i> Colaborador</span>
                            @endif
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $m->cargo ?? '—' }}
                        </td>
                        <td style="vertical-align: middle;">
                            @if($m->user)
                                <span class="text-success"><i class="fa fa-check-circle"></i> <strong>{{ $m->user->name }}</strong></span>
                                <br><small class="text-muted">{{ $m->user->email }}</small>
                            @else
                                <span class="text-muted"><i class="fa fa-minus"></i> <em>Sin usuario del sistema</em></span>
                            @endif
                        </td>
                        <td class="text-center" style="vertical-align: middle;">{{ $m->orden }}</td>
                        <td class="text-center" style="vertical-align: middle;">
                            @if($m->activo)
                                <span class="label label-success">Activo</span>
                            @else
                                <span class="label label-danger">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {!! Form::open(['route' => ['equipo-trabajo.destroy', $m->id], 'method' => 'delete']) !!}
                            <div class='btn-group'>
                                <a href="{!! route('equipo-trabajo.edit', [$m->id]) !!}" class='btn btn-default btn-xs' title="Editar"><i class="glyphicon glyphicon-edit"></i></a>
                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'title' => 'Eliminar', 'onclick' => "return confirm('¿Está seguro de eliminar a este integrante del equipo?')"]) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No se encontraron integrantes registrados. Haga clic en <strong>Nuevo Integrante</strong> para agregar uno.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            @if(isset($miembros))
                <div class="text-center">
                    {{ $miembros->appends(['tipo' => $tipo])->links() }}
                </div>
            @endif
          </div>
        </div>
      </div>
    </div>
@endsection
