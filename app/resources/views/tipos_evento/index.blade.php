@extends('layouts.panel-abm')

@section('title', 'TIPOS DE EVENTO')
@section('subtitle', 'Gestión de Tipos de Eventos.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="title_right">
              <a class="btn btn-primary pull-right" style="margin-bottom: 5px" href="{!! route('tipos-evento.create') !!}">Nuevo Tipo de Evento</a>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tipos as $tipo)
                    <tr>
                        <td>{{ $tipo->id }}</td>
                        <td>{{ $tipo->nombre }}</td>
                        <td>
                            @if($tipo->activo)
                                <span class="label label-success">Activo</span>
                            @else
                                <span class="label label-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            {!! Form::open(['route' => ['tipos-evento.destroy', $tipo->id], 'method' => 'delete']) !!}
                            <div class='btn-group'>
                                <a href="{!! route('tipos-evento.edit', [$tipo->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('¿Está seguro de eliminar este tipo de evento?')"]) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if(isset($tipos))
                {{ $tipos->links() }}
            @endif
          </div>
        </div>
      </div>
    </div>
@stop
