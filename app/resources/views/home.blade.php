@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title d-flex justify-content-between align-items-center">
        <h2>
          <i class="fa fa-tachometer text-primary"></i> 
          Panel de Control <small>Universidad Nacional de Salta</small>
        </h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <div class="well" style="background-color: #f7f9fa; border-left: 4px solid #337ab7; margin-bottom: 20px;">
          <h4 style="margin-top: 0;"><strong>¡Hola, {{ Auth::user()->name }}!</strong></h4>
          <p class="mb-0">
            Te has autenticado correctamente como 
            <span class="label label-primary" style="font-size: 11px;">
              {{ Auth::user()->roles->pluck('name')->implode(', ') ?: 'Usuario' }}
            </span>. 
            Desde aquí puedes gestionar los turnos, dependencias y reservas del Observatorio.
          </p>
        </div>

        <!-- Tarjetas de Métricas Rápidas -->
        <div class="row top_tiles" style="margin: 10px 0 25px 0;">
          <div class="col-md-3 col-sm-6 col-xs-12 tile text-center" style="border-right: 1px solid #e0e0e0;">
            <span class="small text-muted"><i class="fa fa-calendar-check-o text-info"></i> Turnos para Hoy</span>
            <div class="count text-info" style="font-size: 28pt; font-weight: bold;">{{ $turnosHoy }}</div>
            <p class="small">Registrados en la fecha actual</p>
          </div>
          <div class="col-md-3 col-sm-6 col-xs-12 tile text-center" style="border-right: 1px solid #e0e0e0;">
            <span class="small text-muted"><i class="fa fa-clock-o text-success"></i> Próximos Turnos Activos</span>
            <div class="count text-success" style="font-size: 28pt; font-weight: bold;">{{ $turnosPendientes }}</div>
            <p class="small">En fecha actual o futura</p>
          </div>
          <div class="col-md-3 col-sm-6 col-xs-12 tile text-center" style="border-right: 1px solid #e0e0e0;">
            <span class="small text-muted"><i class="fa fa-folder-open text-warning"></i> Proyectos de Extensión</span>
            <div class="count text-warning" style="font-size: 28pt; font-weight: bold;">{{ $totalProyectos }}</div>
            <p class="small">Proyectos habilitados</p>
          </div>
          <div class="col-md-3 col-sm-6 col-xs-12 tile text-center">
            <span class="small text-muted"><i class="fa fa-star text-primary"></i> Tipos de Eventos</span>
            <div class="count text-primary" style="font-size: 28pt; font-weight: bold;">{{ $totalEventos }}</div>
            <p class="small">Categorías de reservas</p>
          </div>
        </div>

        <div class="row">
          <!-- Tabla de Próximas Reservas -->
          <div class="col-md-8 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2><i class="fa fa-list-alt"></i> Próximas Reservas Confirmadas</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                @if($proximasReservas->isEmpty())
                  <div class="text-center py-4 text-muted">
                    <i class="fa fa-calendar-o fa-3x mb-2"></i>
                    <p>No hay reservas pendientes registradas para los próximos días.</p>
                  </div>
                @else
                  <table class="table table-striped table-bordered text-center" style="font-size: 10pt;">
                    <thead>
                      <tr class="bg-light">
                        <th>Código</th>
                        <th>Fecha y Hora</th>
                        <th>Solicitante</th>
                        <th>Dependencia / Trámite</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($proximasReservas as $reserva)
                        <tr>
                          <td><strong class="text-primary">{{ $reserva->codigo }}</strong></td>
                          <td>
                            {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} - {{ $reserva->hora }} hs
                          </td>
                          <td>
                            <strong>{{ $reserva->nombre_apellido }}</strong>
                            <br>
                            <small class="text-muted">{{ $reserva->email }}</small>
                          </td>
                          <td>
                            {{ $reserva->turno_horario->turno_tramite->tramite->dependencia->nombre ?? 'Observatorio' }}
                            <br>
                            <span class="label label-default">
                              {{ $reserva->turno_horario->turno_tramite->tramite->nombre ?? 'Reserva' }}
                            </span>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <div class="text-right">
                    <a href="{{ url('turnosdependenciasreservas') }}" class="btn btn-sm btn-default">
                      Ver todas las reservas <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                @endif
              </div>
            </div>
          </div>

          <!-- Accesos Rápidos (Acciones de Gestión) -->
          <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2><i class="fa fa-rocket"></i> Accesos Rápidos</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <div class="list-group">
                  <a href="{{ url('turnosdependenciasreservas') }}" class="list-group-item">
                    <h5 class="list-group-item-heading"><i class="fa fa-calendar-check-o text-success me-2"></i> Reservas de Turnos</h5>
                    <p class="list-group-item-text small text-muted">Gestión de reservas de usuarios y confirmaciones.</p>
                  </a>
                  <a href="{{ route('estadisticas.index') }}" class="list-group-item">
                    <h5 class="list-group-item-heading"><i class="fa fa-area-chart text-info me-2"></i> Estadísticas y Reportes</h5>
                    <p class="list-group-item-text small text-muted">Ver métricas por estado, dependencia y tipo de trámite.</p>
                  </a>
                  <a href="{{ url('proyectos-extension') }}" class="list-group-item">
                    <h5 class="list-group-item-heading"><i class="fa fa-folder text-warning me-2"></i> Proyectos de Extensión</h5>
                    <p class="list-group-item-text small text-muted">Administrar proyectos habilitados para turnos.</p>
                  </a>
                  @hasrole('ADMINISTRADOR')
                  <a href="{{ url('usuarios') }}" class="list-group-item">
                    <h5 class="list-group-item-heading"><i class="fa fa-users text-primary me-2"></i> Gestión de Usuarios</h5>
                    <p class="list-group-item-text small text-muted">Asignación de roles, activación y permisos.</p>
                  </a>
                  @endhasrole
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@stop