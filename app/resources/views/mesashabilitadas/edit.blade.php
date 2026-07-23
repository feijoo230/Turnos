@extends('layouts.panel-abm')

@section('title', 'EDITAR MESA HABILITADA')
@section('subtitle', 'Modificar disponibilidad de la mesa de atención.')

@section('body')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-edit text-warning"></i> Edición de Mesa Habilitada</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                {!! Form::model($mesahabilitada, ['route' => ['mesashabilitadas.update', $mesahabilitada->id], 'method' => 'patch', 'class' => 'form-horizontal form-label-left']) !!}
                    @include('mesashabilitadas.fields')
                    
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <button onclick="location.href='{!! route('mesashabilitadas.index') !!}'" class="btn btn-default pull-right" type="button">Cancelar</button>
                            <button type="submit" class="btn btn-warning pull-right"><i class="fa fa-save"></i> Guardar Cambios</button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop