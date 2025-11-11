@extends('layouts.panel-abm')

@section('title', 'ROLES-PERMISOS')
@section('subtitle', 'Administración de los permisos por rol.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="title_right">
              <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top">
                {{ Form::open(array('method' => 'get', 'route' => 'rolespermisos.index')) }}
                  <div class="input-group">
                    {{ Form::select('role_id', $roles, $role_id, ['class' => 'form-control']) }}
                    <span class="input-group-btn">
                      {{ Form::submit('Seleccionar', array('class' => 'btn btn-primary')) }}
                    </span>
                  </div>
                {{ Form::close() }}
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @include('rolespermisos.table')
          </div>
        </div>
      </div>
    </div>
@stop