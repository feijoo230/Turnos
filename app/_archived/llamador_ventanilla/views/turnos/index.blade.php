@extends('layouts.panel-abm')

@section('title', 'TURNOS')
@section('subtitle', 'Administración de turnos.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="title_right">
              <a class="btn btn-primary pull-right" style="margin-bottom: 5px" href="{!! url('turnos_admin.llamar_siguiente') !!}">LLAMAR SIGUIENTE</a>  
              
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @include('turnos.table')
          </div>
        </div>
      </div>
    </div>
@stop