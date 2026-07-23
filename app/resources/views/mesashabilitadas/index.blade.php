@extends('layouts.panel-abm')

@section('title', 'MESAS HABILITADAS')
@section('subtitle', 'Mesas de entrada y atención habilitadas para la recepción de turnos.')

@section('body')
<!-- Tarjetas de Métricas Estadísticas (Estilo Nativo Gentelella) -->
<div class="row top_tiles" style="margin: 10px 0 20px 0;">
    <div class="col-md-3 col-sm-6 col-xs-12 tile text-center" style="border-right: 1px solid #e0e0e0;">
        <span class="small text-muted"><i class="fa fa-check-circle text-success"></i> Mesas Habilitadas</span>
        <div class="count text-success" style="font-size: 26pt; font-weight: bold; margin-top: 5px;">{{ $stats['activas'] ?? 0 }}</div>
        <p class="small text-muted mb-0">Atención de turnos activa</p>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 tile text-center" style="border-right: 1px solid #e0e0e0;">
        <span class="small text-muted"><i class="fa fa-pause-circle text-danger"></i> Mesas Inactivas</span>
        <div class="count text-danger" style="font-size: 26pt; font-weight: bold; margin-top: 5px;">{{ $stats['inactivas'] ?? 0 }}</div>
        <p class="small text-muted mb-0">Temporalmente pausadas</p>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 tile text-center" style="border-right: 1px solid #e0e0e0;">
        <span class="small text-muted"><i class="fa fa-desktop text-primary"></i> Total Configuradas</span>
        <div class="count text-primary" style="font-size: 26pt; font-weight: bold; margin-top: 5px;">{{ $stats['total'] ?? 0 }}</div>
        <p class="small text-muted mb-0">Mesas registradas</p>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 tile text-center">
        <span class="small text-muted"><i class="fa fa-pie-chart text-info"></i> Cobertura General</span>
        <div class="count text-info" style="font-size: 26pt; font-weight: bold; margin-top: 5px;">
            @if(($stats['total_dependencias'] ?? 0) > 0)
                {{ round((($stats['activas'] ?? 0) / $stats['total_dependencias']) * 100) }}%
            @else
                0%
            @endif
        </div>
        <p class="small text-muted mb-0">Del total de dependencias</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title d-flex justify-content-between align-items-center">
                <h2>
                    <i class="fa fa-desktop text-success"></i> Mesas de Entrada y Atención Habilitadas
                    <small>Gestión de disponibilidad por dependencia</small>
                </h2>
                <div class="title_right text-right">
                    <a class="btn btn-primary pull-right" style="margin-bottom: 5px;" href="{!! route('mesashabilitadas.create') !!}">
                        <i class="fa fa-plus"></i> Habilitar Nueva Mesa
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @include('mesashabilitadas.table')
            </div>
        </div>
    </div>
</div>
@stop