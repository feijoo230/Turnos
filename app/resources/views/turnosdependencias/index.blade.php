@extends('layouts.panel-abm')

@section('title', 'TRUNOS POR DEPENDENCIAS')
@section('subtitle', 'Administración de los turnos de dependencias.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="title_right">
              <a class="btn btn-primary pull-right" style="margin-bottom: 5px" href="{!! route('turnosdependencias.create') !!}">Nuevo</a>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @include('turnosdependencias.table')
          </div>
        </div>
      </div>
    </div>
@stop