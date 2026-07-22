@extends('layouts.panel-abm')

@section('title', 'TURNOS POR TRAMITES')
@section('subtitle', 'Detalles del Turno por Trámite')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              <div class="ln_solid"></div>
              <form class="form-horizontal form-label-left">
                  
                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Dependencia:</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                          {{ $turnostramite->tramite->dependencia->nombre ?? 'N/A' }}
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Trámite:</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                          {{ $turnostramite->tramite->nombre ?? 'N/A' }}
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Fecha Desde:</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                          {{ $turnostramite->fecha_desde->format('d/m/Y') }}
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Fecha Hasta:</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                          {{ $turnostramite->fecha_hasta->format('d/m/Y') }}
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipo de Evento:</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                          {{ $turnostramite->tipoEvento->nombre ?? 'Ninguno' }}
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Proyecto de Extensión:</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                          {{ $turnostramite->proyectoExtension->nombre ?? 'Ninguno' }}
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Responsable:</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                          {{ $turnostramite->responsable->name ?? 'Ninguno' }}
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Activo:</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                          @if($turnostramite->activo)
                              <span class="label label-success">Sí</span>
                          @else
                              <span class="label label-danger">No</span>
                          @endif
                      </div>
                  </div>

                  <div class="ln_solid"></div>
                  
                  <h4>Horarios Programados</h4>
                  <table class="table table-bordered">
                      <thead>
                          <tr>
                              <th>Hora Inicio</th>
                              <th>Hora Fin</th>
                              <th>Duración (min)</th>
                              <th>Cantidad Turnos</th>
                              <th>Días Activos</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach($turnostramite->turnosHorarios as $horario)
                          <tr>
                              <td>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</td>
                              <td>{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</td>
                              <td>{{ $horario->duracion_minutos }}</td>
                              <td>{{ $horario->cantidad_turnos }}</td>
                              <td>
                                  @if($horario->lunes) <span class="badge bg-blue">Lu</span> @endif
                                  @if($horario->martes) <span class="badge bg-blue">Ma</span> @endif
                                  @if($horario->miercoles) <span class="badge bg-blue">Mi</span> @endif
                                  @if($horario->jueves) <span class="badge bg-blue">Ju</span> @endif
                                  @if($horario->viernes) <span class="badge bg-blue">Vi</span> @endif
                                  @if($horario->sabado) <span class="badge bg-blue">Sa</span> @endif
                                  @if($horario->domingo) <span class="badge bg-blue">Do</span> @endif
                                  
                                  @if(!$horario->activo)
                                    <span class="label label-danger">INACTIVO</span>
                                  @endif
                              </td>
                          </tr>
                          @endforeach
                      </tbody>
                  </table>

                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <button onclick="location.href='{!! route('turnostramites.index') !!}'" class="btn btn-primary pull-right" type="button">Volver</button>
                    </div>
                  </div>
              </form>
          </div>
        </div>
      </div>
    </div>
@stop
