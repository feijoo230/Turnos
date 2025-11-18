@extends('layouts.panel-abm')

@section('title', 'RESERVA DE TURNO')
@section('subtitle', 'Detalle de la reserva de turno.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              <div class="ln_solid"></div>
              @include('turnosdependenciasreservas.show_fields')
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                  <a href="{!! route('turnos.print', [$reserva->id]) !!}" class="btn btn-success">Descargar Comprobante</a>
                  <button onclick="location.href='{!! route('turnosdependenciasreservas.index') !!}'" class="btn btn-primary" type="button">Atras</button>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
@stop