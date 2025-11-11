@extends('layouts.panel-abm')

@section('title', 'USUARIOS')
@section('subtitle', 'Personas que administran alguna/s funcionalidades del sistema.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              <div class="ln_solid"></div>
              @include('usuarios.show_fields')
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                  <button onclick="location.href='{!! route('usuarios.index') !!}'" class="btn btn-primary" type="button">Atras</button>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
@stop
