@extends('layouts.panel-abm')

@section('title', 'DEPENDENCIAS')
@section('subtitle', 'Organigrama y estructura administrativa de la UNSa.')

@section('body')
<!-- Tarjetas de Métricas Estadísticas (Estilo Nativo Gentelella) -->
<div class="row top_tiles" style="margin: 10px 0 20px 0;">
    <div class="col-md-3 col-sm-6 col-xs-12 tile text-center" style="border-right: 1px solid #e0e0e0;">
        <span class="small text-muted"><i class="fa fa-sitemap text-primary"></i> Total Dependencias</span>
        <div class="count text-primary" style="font-size: 26pt; font-weight: bold; margin-top: 5px;">{{ $stats['total'] ?? 0 }}</div>
        <p class="small text-muted mb-0">Estructura general</p>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 tile text-center" style="border-right: 1px solid #e0e0e0;">
        <span class="small text-muted"><i class="fa fa-university text-success"></i> Unidades Académicas</span>
        <div class="count text-success" style="font-size: 26pt; font-weight: bold; margin-top: 5px;">{{ $stats['unidades_academicas'] ?? 0 }}</div>
        <p class="small text-muted mb-0">Facultades y sedes</p>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 tile text-center" style="border-right: 1px solid #e0e0e0;">
        <span class="small text-muted"><i class="fa fa-building-o text-warning"></i> Áreas / Departamentos</span>
        <div class="count text-warning" style="font-size: 26pt; font-weight: bold; margin-top: 5px;">{{ $stats['subdependencias'] ?? 0 }}</div>
        <p class="small text-muted mb-0">Sub-dependencias</p>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 tile text-center">
        <span class="small text-muted"><i class="fa fa-desktop text-info"></i> Mesas Habilitadas</span>
        <div class="count text-info" style="font-size: 26pt; font-weight: bold; margin-top: 5px;">{{ $stats['mesas_activas'] ?? 0 }}</div>
        <p class="small text-muted mb-0">Con atención activa</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title d-flex justify-content-between align-items-center">
                <h2>
                    <i class="fa fa-sitemap text-primary"></i> Estructura Organizacional
                    <small>Seleccione el modo de visualización</small>
                </h2>
                <div class="title_right text-right">
                    <a class="btn btn-primary pull-right" style="margin-bottom: 5px;" href="{!! route('dependencias.create') !!}">
                        <i class="fa fa-plus"></i> Nueva Dependencia
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                    <li class="active">
                        <a href="#tab_tree" data-toggle="tab"><i class="fa fa-sitemap"></i> Vista Jerárquica (Organigrama)</a>
                    </li>
                    <li>
                        <a href="#tab_table" data-toggle="tab"><i class="fa fa-table"></i> Vista Tabla Detallada</a>
                    </li>
                </ul>
                
                @include('dependencias.table')
            </div>
        </div>
    </div>
</div>
@stop