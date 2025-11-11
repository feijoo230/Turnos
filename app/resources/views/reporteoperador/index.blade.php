@extends('layouts.panel-abm')

@section('title', 'REPORTE DE OPERADORES')
@section('subtitle', '')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">          
          <div class="x_content">
          	@if ($errors->any())
			  <div class="alert alert-danger">
			      <ul>
			          @foreach ($errors->all() as $error)
			              <li>{{ $error }}</li>
			          @endforeach
			      </ul>
			  </div>
			  <div class="clearfix"></div>
			@endif
            @include('reporteoperador.table')
          </div>
        </div>
      </div>
    </div>
@stop