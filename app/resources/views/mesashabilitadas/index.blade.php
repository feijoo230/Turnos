@extends('layouts.panel-abm')

@section('title', 'MESAS HABILITADAS')
@section('subtitle', 'Mesas habilitadas para recibir documentación digital.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="title_right">
              <a class="btn btn-primary pull-right" style="margin-bottom: 5px" href="{!! route('mesashabilitadas.create') !!}">Nuevo</a>  
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @include('mesashabilitadas.table')
          </div>
        </div>
      </div>
    </div>
@stop