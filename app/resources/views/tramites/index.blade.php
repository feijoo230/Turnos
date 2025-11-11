@extends('layouts.panel-abm')

@section('title', 'TRAMITES')
@section('subtitle', 'Tramites on-line adjuntando documentos digitales.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="title_right">
              <a class="btn btn-primary pull-right" style="margin-bottom: 5px" href="{!! route('tramites.create') !!}">Nuevo</a>  
              <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top">
                {{ Form::open(array('method' => 'post', 'route' => array('tramites.buscar'))) }}
                  <div class="input-group">
                      {!! Form::text('text_buscar', null, ['class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Ingrese su busqueda...']) !!}
                      <span class="input-group-btn">
                        {{ Form::submit('Buscar', array('class' => 'btn btn-primary')) }}
                      </span>
                  </div>
                {{ Form::close() }}
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @include('tramites.table')
            <div class="text-center">{{ $tramites->links() }}</div>
          </div>
        </div>
      </div>
    </div>
@stop

