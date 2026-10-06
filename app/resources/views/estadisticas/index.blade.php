@extends('layouts.app')

@section('content')
<style>
    .kpi-card {
        background: #ffffff;
        border-radius: 8px;
        padding: 18px 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.09);
    }
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 4px;
    }
    .kpi-blue::before { background: #0284c7; }
    .kpi-green::before { background: #10b981; }
    .kpi-purple::before { background: #8b5cf6; }
    .kpi-amber::before { background: #f59e0b; }

    .kpi-title {
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 6px;
    }
    .kpi-value {
        font-size: 30px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.1;
        margin-bottom: 6px;
    }
    .kpi-footer {
        font-size: 12px;
        color: #94a3b8;
    }
    .kpi-icon {
        position: absolute;
        right: 18px;
        top: 20px;
        font-size: 38px;
        opacity: 0.2;
    }
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
    }
    .chart-container-lg {
        position: relative;
        height: 290px;
        width: 100%;
    }
    .filter-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 18px;
        margin-bottom: 22px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
</style>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title" style="border-bottom: 2px solid #e2e8f0; padding-bottom: 12px;">
                <h2 style="font-weight: 700; color: #0f172a;">
                    <i class="fa fa-telescope text-primary"></i> Métricas y Estadísticas del Observatorio
                    <small style="color: #64748b; font-size: 14px;">Análisis de Visitas, Afluencia Escolar y Actividades Astronómicas</small>
                </h2>
                <div class="title_right text-right">
                    <button type="button" class="btn btn-default btn-sm" onclick="window.print();" title="Imprimir reporte actual">
                        <i class="fa fa-print"></i> Imprimir Reporte
                    </button>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="x_content" style="padding-top: 15px;">
                
                <!-- Barra de Filtros Interactivos -->
                <div class="filter-bar">
                    <form id="filterForm" class="form-inline row" onsubmit="event.preventDefault(); loadMetrics();">
                        <div class="col-md-5 col-sm-6 col-xs-12 form-group" style="margin-bottom: 5px;">
                            <label style="margin-right: 8px; font-weight: 600; color: #334155;"><i class="fa fa-university text-primary"></i> Dependencia:</label>
                            <select id="dependenciaSelect" class="form-control input-sm" style="min-width: 260px;" onchange="loadMetrics()">
                                <option value="27" selected>🔭 Observatorio Astronómico Dr. Elvio Alanís</option>
                                <option value="todas">--- Todas las Dependencias (Global) ---</option>
                                @foreach($dependencias as $idDep => $nomDep)
                                    @if($idDep != 27)
                                        <option value="{{ $idDep }}">{{ $nomDep }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-12 form-group" style="margin-bottom: 5px;">
                            <label style="margin-right: 8px; font-weight: 600; color: #334155;"><i class="fa fa-calendar text-info"></i> Período:</label>
                            <select id="periodoSelect" class="form-control input-sm" onchange="loadMetrics()">
                                <option value="historico" selected>Todo el Historial</option>
                                <option value="30d">Últimos 30 días</option>
                                <option value="90d">Últimos 90 días</option>
                                <option value="ano">Año en curso</option>
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-2 col-xs-12 text-right" style="margin-bottom: 5px;">
                            <button type="button" class="btn btn-primary btn-sm" onclick="loadMetrics()">
                                <i class="fa fa-refresh"></i> Actualizar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 1. Tarjetas KPI Principales -->
                <div class="row">
                    <!-- KPI 1: Total Reservas -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="kpi-card kpi-blue">
                            <i class="fa fa-calendar-check-o kpi-icon text-primary"></i>
                            <div class="kpi-title">Reservas Totales</div>
                            <div class="kpi-value" id="kpiTotalTurnos">—</div>
                            <div class="kpi-footer">
                                <span class="text-success"><strong id="kpiAtendidos">0</strong> atendidos</span> · 
                                <span class="text-muted"><strong id="kpiPendientes">0</strong> activos</span>
                            </div>
                        </div>
                    </div>

                    <!-- KPI 2: Total Visitantes Estimados -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="kpi-card kpi-green">
                            <i class="fa fa-users kpi-icon text-success"></i>
                            <div class="kpi-title">Visitantes Totales</div>
                            <div class="kpi-value" id="kpiTotalVisitantes">—</div>
                            <div class="kpi-footer">
                                <span>Público general + delegaciones escolares</span>
                            </div>
                        </div>
                    </div>

                    <!-- KPI 3: Contingentes e Instituciones -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="kpi-card kpi-purple">
                            <i class="fa fa-graduation-cap kpi-icon" style="color: #8b5cf6;"></i>
                            <div class="kpi-title">Delegaciones Escolares</div>
                            <div class="kpi-value" id="kpiTotalDelegaciones">—</div>
                            <div class="kpi-footer">
                                <span>Colegios y contingentes recibidos</span>
                            </div>
                        </div>
                    </div>

                    <!-- KPI 4: Tasa de Efectividad -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="kpi-card kpi-amber">
                            <i class="fa fa-check-circle-o kpi-icon text-warning"></i>
                            <div class="kpi-title">Tasa de Efectividad</div>
                            <div class="kpi-value" id="kpiTasaEfectividad">—%</div>
                            <div class="kpi-footer">
                                <span id="kpiCanceladosNote">Cancelaciones registradas: <strong>0</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Fila 1 de Gráficos: Evolución Temporal y Modalidad -->
                <div class="row">
                    <!-- Evolución Temporal -->
                    <div class="col-md-8 col-sm-12 col-xs-12">
                        <div class="x_panel" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                            <div class="x_title">
                                <h2><i class="fa fa-line-chart text-primary"></i> Evolución de Visitas <small>Reservas vs Personas que asistieron</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="chart-container-lg">
                                    <canvas id="chartEvolucion"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Distribución por Modalidad -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="x_panel" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                            <div class="x_title">
                                <h2><i class="fa fa-pie-chart text-info"></i> Modalidad de Visita <small>Tipos de público</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="chart-container-lg">
                                    <canvas id="chartModalidad"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Fila 2 de Gráficos: Actividades y Estado -->
                <div class="row">
                    <!-- Trámites y Actividades más Solicitadas -->
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="x_panel" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                            <div class="x_title">
                                <h2><i class="fa fa-bar-chart text-primary"></i> Actividades más Solicitadas <small>Observatorio</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="chart-container">
                                    <canvas id="chartTramite"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estado de las Reservas -->
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="x_panel" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                            <div class="x_title">
                                <h2><i class="fa fa-tasks text-warning"></i> Estado de los Turnos <small>Flujo de atención</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="chart-container">
                                    <canvas id="chartEstado"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Fila 3: Top Instituciones y Franjas Horarias -->
                <div class="row">
                    <!-- Instituciones Educativas -->
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="x_panel" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                            <div class="x_title">
                                <h2><i class="fa fa-university text-success"></i> Instituciones y Colegios <small>Con mayor concurrencia</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="chart-container">
                                    <canvas id="chartInstituciones"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Franjas Horarias -->
                    <div class="col-md-5 col-sm-12 col-xs-12">
                        <div class="x_panel" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                            <div class="x_title">
                                <h2><i class="fa fa-moon-o text-primary"></i> Horario de Visita <small>Diurno vs Nocturno</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="chart-container">
                                    <canvas id="chartHorario"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
let chartEvolucionInstance = null;
let chartModalidadInstance = null;
let chartTramiteInstance = null;
let chartEstadoInstance = null;
let chartInstitucionesInstance = null;
let chartHorarioInstance = null;

$(document).ready(function() {
    // Parche seguro para gentellela/appl.js si sobreescribe Chart.defaults.global.legend
    if (window.Chart && Chart.defaults && Chart.defaults.global && Chart.defaults.global.legend) {
        if (!Chart.defaults.global.legend.labels) {
            Chart.defaults.global.legend.labels = {
                fontSize: 12,
                fontFamily: "'Inter', 'Helvetica Neue', 'Arial', sans-serif",
                fontColor: '#475569',
                boxWidth: 35,
                padding: 10
            };
        }
    }

    loadMetrics();
});

function loadMetrics() {
    const dep = $('#dependenciaSelect').val();
    const per = $('#periodoSelect').val();
    const url = '{{ route('estadisticas.data') }}' + '?dependencia_id=' + encodeURIComponent(dep) + '&periodo=' + encodeURIComponent(per);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            renderKPIs(data.kpis);
            renderCharts(data);
        })
        .catch(err => {
            console.error('Error cargando métricas:', err);
        });
}

function renderKPIs(kpis) {
    if (!kpis) return;
    $('#kpiTotalTurnos').text(kpis.total_turnos.toLocaleString());
    $('#kpiTotalVisitantes').text(kpis.total_visitantes.toLocaleString());
    $('#kpiTotalDelegaciones').text(kpis.total_delegaciones.toLocaleString());
    $('#kpiTasaEfectividad').text(kpis.tasa_efectividad + '%');

    $('#kpiAtendidos').text(kpis.total_atendidos);
    $('#kpiPendientes').text(kpis.total_pendientes);
    $('#kpiCanceladosNote').html('Cancelaciones: <strong class="text-danger">' + kpis.total_cancelados + '</strong>');
}

function renderCharts(data) {
    // 1. Chart Evolución (Doble línea)
    if (chartEvolucionInstance) chartEvolucionInstance.destroy();
    const ctxEvo = document.getElementById('chartEvolucion').getContext('2d');
    chartEvolucionInstance = new Chart(ctxEvo, {
        type: 'line',
        data: {
            labels: data.evolucion.labels,
            datasets: [
                {
                    label: 'Visitantes / Asistentes',
                    data: data.evolucion.personas,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.12)',
                    borderWidth: 2.5,
                    pointRadius: 4,
                    fill: true,
                    lineTension: 0.25
                },
                {
                    label: 'Reservas Registradas',
                    data: data.evolucion.turnos,
                    borderColor: '#0284c7',
                    backgroundColor: 'rgba(2, 132, 199, 0.08)',
                    borderWidth: 2,
                    pointRadius: 3.5,
                    fill: true,
                    lineTension: 0.25
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
            }
        }
    });

    // 2. Chart Modalidad (Doughnut)
    if (chartModalidadInstance) chartModalidadInstance.destroy();
    const ctxMod = document.getElementById('chartModalidad').getContext('2d');
    chartModalidadInstance = new Chart(ctxMod, {
        type: 'doughnut',
        data: {
            labels: data.modalidad.labels,
            datasets: [{
                data: data.modalidad.data,
                backgroundColor: [
                    '#8b5cf6', // Escolar / Institucional
                    '#38bdf8', // Grupal
                    '#0284c7', // Individual
                    '#f59e0b'  // Eventos Especiales
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { position: 'bottom' }
        }
    });

    // 3. Chart Trámites / Actividades (Horizontal Bar)
    if (chartTramiteInstance) chartTramiteInstance.destroy();
    const ctxTra = document.getElementById('chartTramite').getContext('2d');
    chartTramiteInstance = new Chart(ctxTra, {
        type: 'horizontalBar',
        data: {
            labels: data.tramite.labels,
            datasets: [
                {
                    label: 'Cantidad de Turnos',
                    data: data.tramite.data,
                    backgroundColor: 'rgba(2, 132, 199, 0.85)'
                },
                {
                    label: 'Total Asistentes',
                    data: data.tramite.personas,
                    backgroundColor: 'rgba(16, 185, 129, 0.85)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
            }
        }
    });

    // 4. Chart Estado (Doughnut)
    if (chartEstadoInstance) chartEstadoInstance.destroy();
    const ctxEst = document.getElementById('chartEstado').getContext('2d');
    chartEstadoInstance = new Chart(ctxEst, {
        type: 'doughnut',
        data: {
            labels: data.estado.labels,
            datasets: [{
                data: data.estado.data,
                backgroundColor: [
                    '#f59e0b', // Pendiente
                    '#0284c7', // Confirmado
                    '#10b981', // Atendido
                    '#ef4444'  // Cancelado
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { position: 'bottom' }
        }
    });

    // 5. Chart Instituciones (Horizontal Bar)
    if (chartInstitucionesInstance) chartInstitucionesInstance.destroy();
    const ctxIns = document.getElementById('chartInstituciones').getContext('2d');
    chartInstitucionesInstance = new Chart(ctxIns, {
        type: 'horizontalBar',
        data: {
            labels: data.instituciones.labels.length > 0 ? data.instituciones.labels : ['Sin instituciones registradas'],
            datasets: [{
                label: 'Estudiantes / Integrantes recibidos',
                data: data.instituciones.data.length > 0 ? data.instituciones.data : [0],
                backgroundColor: 'rgba(139, 92, 246, 0.85)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
            }
        }
    });

    // 6. Chart Horarios (Bar)
    if (chartHorarioInstance) chartHorarioInstance.destroy();
    const ctxHor = document.getElementById('chartHorario').getContext('2d');
    chartHorarioInstance = new Chart(ctxHor, {
        type: 'bar',
        data: {
            labels: data.horario.labels.length > 0 ? data.horario.labels : ['Sin datos'],
            datasets: [{
                label: 'Cantidad de Visitas',
                data: data.horario.data.length > 0 ? data.horario.data : [0],
                backgroundColor: ['#0f172a', '#0284c7']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
            }
        }
    });
}
</script>
@endsection
