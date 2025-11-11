@extends('layouts.panel-abm')

@section('title', 'TURNOS POR TRAMITE')
@section('subtitle', 'Turnos que se pueden solicitar por cada trámite.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="title_right">
              <a class="btn btn-primary pull-right" style="margin-bottom: 5px" href="{!! route('turnostramites.create') !!}">Nuevo</a>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @include('turnostramites.table')
          </div>
        </div>
      </div>
    </div>
@stop
