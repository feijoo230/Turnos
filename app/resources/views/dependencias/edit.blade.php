@extends('layouts.panel-abm')

@section('title', 'EDITAR DEPENDENCIA')
@section('subtitle', 'Modificar datos de ' . $dependencia->nombre)

@section('body')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-edit text-warning"></i> Edición de Dependencia</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                {!! Form::model($dependencia, ['route' => ['dependencias.update', $dependencia->id], 'method' => 'patch', 'class' => 'form-horizontal form-label-left']) !!}
                    @include('dependencias.fields')
                    
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <button onclick="location.href='{!! route('dependencias.index') !!}'" class="btn btn-default pull-right" type="button">Cancelar</button>
                            <button type="submit" class="btn btn-warning pull-right"><i class="fa fa-save"></i> Actualizar Dependencia</button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop