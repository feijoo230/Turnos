@extends('layouts.panel-abm')

@section('title', 'TRAMITES POR DEPENDENCIAS')
@section('subtitle', 'Tramites que el público en general puede realizar en una dependencia.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="title_right">
              <a class="btn btn-primary pull-right" style="margin-bottom: 5px" href="{!! route('tramitesdependencias.create') !!}">Nuevo</a>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @include('tramitesdependencias.table')
          </div>
        </div>
      </div>
    </div>
@stop