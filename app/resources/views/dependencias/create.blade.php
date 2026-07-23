@extends('layouts.panel-abm')

@section('title', 'NUEVA DEPENDENCIA')
@section('subtitle', 'Crear una nueva área, facultad o departamento administrativo.')

@section('body')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-plus-circle text-primary"></i> Formulario de Alta de Dependencia</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                {!! Form::open(['route' => 'dependencias.store', 'class' => 'form-horizontal form-label-left']) !!}
                    @include('dependencias.fields')
                    
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <button onclick="location.href='{!! route('dependencias.index') !!}'" class="btn btn-default pull-right" type="button">Cancelar</button>
                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Guardar Dependencia</button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop