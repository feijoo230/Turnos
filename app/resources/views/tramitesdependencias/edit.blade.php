@extends('layouts.panel-abm')

@section('title', 'TRAMITES POR DEPENDENCIAS')
@section('subtitle', 'Tramites que el público en general puede realizar en una dependencia.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              <div class="ln_solid"></div>
                {!! Form::model($tramitedependencia, ['route' => ['tramitesdependencias.update', $tramitedependencia->id], 'method' => 'patch', 'class' => 'form-horizontal form-label-left']) !!}
                  @include('tramitesdependencias.fields')
                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <button onclick="location.href='{!! route('tramitesdependencias.index') !!}'" class="btn btn-primary  pull-right" type="button">Cancelar</button>
                      <button type="submit" class="btn btn-success pull-right">Guardar</button>
                    </div>
                  </div>
                </form>
          </div>
        </div>
      </div>
    </div>
@stop