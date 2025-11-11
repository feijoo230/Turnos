@extends('layouts.panel-abm')

@section('title', 'USUARIOS')
@section('subtitle', 'Usuario del Sistema.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="title_right">
              <a class="btn btn-primary pull-right" style="margin-bottom: 5px" href="{!! route('usuarios.create') !!}">Nuevo</a>  
              <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top">
                {{ Form::open(array('method' => 'get', 'route' => array('usuarios.buscar'))) }}
                  <div class="input-group">
                      {!! Form::text('text_buscar', (isset($input['text_buscar'])? $input['text_buscar'] : null), ['class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Ingrese su busqueda...']) !!}
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
            @include('parts.message')
            @include('usuarios.table')
            @if (isset($input))
            <div class="text-center">{{ $usuarios->appends(['text_buscar' => request('text_buscar')])->links() }}</div>
            @else
            <div class="text-center">{{ $usuarios->links() }}</div>
            @endif
          </div>
        </div>
      </div>
    </div>
@stop