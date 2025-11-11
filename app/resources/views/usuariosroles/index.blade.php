@extends('layouts.panel-abm')

@section('title', 'USUARIO - ROLES')
@section('subtitle', 'Una persona puede tener uno o mas roles.')

@section('body')
    <div class="clearfix"></div>

        @include('flash::message')

    <div class="clearfix"></div>


    <div class="x_panel">          
        <div class="x_content">
          {{ Form::open(['url' => 'usuariosroles.guardar', '']) }}
              <table class="table table-striped table-bordered" id="permisos-table">
                  <thead>
                      <th>Rol</th>
                      <th class="text-center">¿Asignar rol?</th>
                  </thead>
                  <tbody>
                  @foreach($aRoles as $clave => $rol)
                      <tr>
                          <td>{!! $rol['name'] !!}</td>
                          <td class="text-center">{{ Form::checkbox('id_roles[]', $clave, $rol['tiene_rol']) }}</td>
                      </tr>
                  @endforeach
                  </tbody>
              </table>
              {{ Form::hidden('id_usuario', $id_usuario) }}
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <button onclick="location.href='{!! route('usuarios.index') !!}'" class="btn btn-primary pull-right" type="button">Cancelar</button>
                  {!! Form::submit('Guardar', ['class' => 'btn btn-success pull-right']) !!}
                </div>
              </div>
          {{ Form::close() }}
        </div>
      </div>
    </div>
@endsection
