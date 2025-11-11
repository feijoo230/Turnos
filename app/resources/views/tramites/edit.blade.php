@extends('layouts.panel-abm')

@section('title', 'TRAMITES')
@section('subtitle', 'Editar datos de tramites digitales.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              <div class="ln_solid"></div>
                {!! Form::model($tramite, ['route' => ['tramites.update', $tramite->id], 'files'=>'true', 'method' => 'patch', 'class' => 'form-horizontal form-label-left']) !!}
                  @include('tramites.fields')
                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <button onclick="location.href='{!! route('tramites.index') !!}'" class="btn btn-primary  pull-right" type="button">Cancelar</button>
                      <button type="submit" class="btn btn-success pull-right">Guardar</button>
                    </div>
                  </div>
                </form>
          </div>
        </div>
      </div>
    </div>
@stop