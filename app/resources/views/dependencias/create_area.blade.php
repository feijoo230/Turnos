@extends('layouts.panel-abm')

@section('title', 'AREAS/DIRECCIONES/DEPENDENCIAS')
@section('subtitle', 'Organigrama de dependencias de la UNSa.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              <div class="ln_solid"></div>
                {!! Form::open(['url' => 'dependencias.store_area', 'class' => 'form-horizontal form-label-left']) !!}
                  @include('dependencias.fields')
                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <button onclick="location.href='{!! route('dependencias.index') !!}'" class="btn btn-primary pull-right" type="button">Cancelar</button>
                      <button type="submit" class="btn btn-success pull-right">Guardar</button>
                    </div>
                  </div>
                </form>
          </div>
        </div>
      </div>
    </div>
@stop