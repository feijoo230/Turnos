@extends('layouts.panel-abm')

@section('title', 'HABILITAR MESA')
@section('subtitle', 'Seleccione la mesa o dependencia a dar de alta para turnos.')

@section('body')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-desktop text-success"></i> Alta de Mesa Habilitada</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                {!! Form::open(['route' => 'mesashabilitadas.store', 'class' => 'form-horizontal form-label-left']) !!}
                    @include('mesashabilitadas.fields')
                    
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <button onclick="location.href='{!! route('mesashabilitadas.index') !!}'" class="btn btn-default pull-right" type="button">Cancelar</button>
                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-check"></i> Habilitar Mesa</button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop