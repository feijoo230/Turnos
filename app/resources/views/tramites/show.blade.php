@extends('layouts.panel-abm')

@section('title', 'TRAMITE DIGITAL')
@section('subtitle', 'Mostrar datos completos del tramite digital.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
            @include('tramites.show_fields')
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                  <button onclick="location.href='{!! route('tramites.index') !!}'" class="btn btn-primary" type="button">Atras</button>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
@stop