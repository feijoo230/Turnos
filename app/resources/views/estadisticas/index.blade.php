@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Panel de Estadísticas y Reportes <small>Resumen de Turnos</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    
                    <div class="row">
                        <!-- Evolución Temporal -->
                        <div class="col-md-8 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Evolución de Turnos <small>(Últimos 30 días)</small></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <canvas id="chartEvolucion" height="100"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Turnos por Estado -->
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Turnos por Estado</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <canvas id="chartEstado" height="220"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Turnos por Dependencia -->
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Top 10 Dependencias <small>Más demandadas</small></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <canvas id="chartDependencia" height="150"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Turnos por Trámite -->
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Top 10 Trámites <small>Más solicitados</small></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <canvas id="chartTramite" height="150"></canvas>
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
$(document).ready(function() {
    // Parche: appl.js sobreescribe Chart.defaults.global.legend destruyendo 'labels'
    if (Chart && Chart.defaults && Chart.defaults.global && Chart.defaults.global.legend) {
        if (!Chart.defaults.global.legend.labels) {
            Chart.defaults.global.legend.labels = {
                fontSize: 12,
                fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
                fontColor: '#666',
                boxWidth: 40,
                padding: 10
            };
        }
    }

    fetch('{{ route('estadisticas.data') }}')
        .then(response => response.json())
        .then(data => {
            const bgColors = [
                'rgba(54, 162, 235, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 159, 64, 0.7)',
                'rgba(199, 199, 199, 0.7)',
                'rgba(83, 102, 255, 0.7)',
                'rgba(40, 159, 64, 0.7)',
                'rgba(210, 199, 199, 0.7)'
            ];

            // 1. Chart Evolución
            new Chart(document.getElementById('chartEvolucion'), {
                type: 'line',
                data: {
                    labels: data.evolucion.labels,
                    datasets: [{
                        label: 'Cantidad de Turnos',
                        data: data.evolucion.data,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                        lineTension: 0.3
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // 2. Chart Estado
            new Chart(document.getElementById('chartEstado'), {
                type: 'doughnut',
                data: {
                    labels: data.estado.labels,
                    datasets: [{
                        data: data.estado.data,
                        backgroundColor: [
                            'rgba(255, 206, 86, 0.7)', // Pendiente
                            'rgba(54, 162, 235, 0.7)', // Confirmado
                            'rgba(75, 192, 192, 0.7)', // Atendido
                            'rgba(255, 99, 132, 0.7)'  // Cancelado
                        ]
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // 3. Chart Dependencia
            new Chart(document.getElementById('chartDependencia'), {
                type: 'horizontalBar',
                data: {
                    labels: data.dependencia.labels,
                    datasets: [{
                        label: 'Cantidad de Turnos',
                        data: data.dependencia.data,
                        backgroundColor: bgColors
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false
                }
            });

            // 4. Chart Tramite
            new Chart(document.getElementById('chartTramite'), {
                type: 'horizontalBar',
                data: {
                    labels: data.tramite.labels,
                    datasets: [{
                        label: 'Cantidad de Turnos',
                        data: data.tramite.data,
                        backgroundColor: bgColors
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false
                }
            });
        })
        .catch(error => console.error('Error cargando estadísticas:', error));
});
</script>
@endsection
