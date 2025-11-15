@extends('layouts.panel-abm')

@section('title', 'EDITAR RESERVA DE TURNO')
@section('subtitle', 'Editar la reserva de turno.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              {!! Form::model($reserva, ['route' => ['turnosdependenciasreservas.update', $reserva->id], 'method' => 'patch']) !!}
              <div class="ln_solid"></div>
              @include('turnosdependenciasreservas.edit_fields')
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                  {{ Form::submit('Guardar', array('class' => 'btn btn-primary')) }}
                  <button onclick="location.href='{!! route('turnosdependenciasreservas.index') !!}'" class="btn btn-primary" type="button">Atras</button>
                </div>
              </div>
              {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
@stop