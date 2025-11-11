@extends('layouts.panel-abm')

@section('title', 'PASES')
@section('subtitle', 'Listado de pases de un tramite determiando.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              <div class="ln_solid"></div>
                {!! Form::open(['route' => 'pases.store', 'class' => 'form-horizontal form-label-left']) !!}
                  @include('pases.fields')
                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <button onclick="location.href='{!! route('pases.index') !!}'" class="btn btn-primary pull-right" type="button">Cancelar</button>
                      <button type="submit" class="btn btn-success pull-right">Guardar</button>
                    </div>
                  </div>
                </form>
          </div>
        </div>
      </div>
    </div>
@stop