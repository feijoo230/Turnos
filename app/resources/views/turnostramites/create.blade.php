@extends('layouts.panel-abm')

@section('title', 'TRUNOS POR TRAMITES')
@section('subtitle', 'Administración de los turnos por tramite.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              <div class="ln_solid"></div>
                {!! Form::open(['route' => 'turnostramites.store', 'class' => 'form-horizontal form-label-left']) !!}
                  @include('turnostramites.fields')
                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <button type="submit" class="btn btn-success pull-right">Guardar</button>
                        <button onclick="location.href='{!! route('turnostramites.index') !!}'" class="btn btn-primary pull-right" type="button">Cancelar</button>
                    </div>
                  </div>
                </form>
          </div>
        </div>
      </div>
    </div>
@stop
