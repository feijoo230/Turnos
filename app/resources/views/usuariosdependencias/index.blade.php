@extends('layouts.panel-abm')

@section('title', 'USUARIO - DEPENDENCIAS')
@section('subtitle', 'Un usuario puede administrar la mesa de entradas de una o varias dependencias.')

@section('body')
    <div class="clearfix"></div>

        @include('flash::message')

    <div class="clearfix"></div>


    <div class="x_panel">          
        <div class="x_content">
          {{ Form::open(['url' => 'usuariosdependencias.guardar', '']) }}
              <table class="table table-striped table-bordered" id="permisos-table">
                  <thead>
                      <th>Dependencias</th>
                      <th class="text-center">¿Asignar dependencia?</th>
                  </thead>
                  <tbody>
                  @foreach($aRoles as $clave => $rol)
                      <tr>
                          <td>{!! $rol['nombre'] !!}</td>
                          <td class="text-center">{{ Form::checkbox('id_dependencias[]', $clave, $rol['tiene_dependencia']) }}</td>
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
