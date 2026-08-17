@extends('layouts.frontend')

@section('content')
<style>
    /* Expandir ancho únicamente para la vista de Mis Turnos */
    .masthead .col-md-9.col-lg-7.col-xl-6 {
        max-width: 100% !important;
        flex: 0 0 100% !important;
        width: 100% !important;
    }
</style>
<div class="w-100 my-4 text-start">
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 px-4">
            <h4 class="mb-0 text-white font-weight-bold"><i class="fas fa-calendar-check me-2"></i> Mis Turnos y Reservas</h4>
            <a href="{{ url('/') }}" class="btn btn-light btn-sm font-weight-bold shadow-sm"><i class="fas fa-plus-circle me-1"></i> Solicitar Nuevo Turno</a>
        </div>
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show font-weight-bold mb-3" role="alert" style="border-radius: 10px;">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show font-weight-bold mb-3" role="alert" style="border-radius: 10px;">
                    <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($reservas->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times text-muted fa-4x mb-3"></i>
                    <h5 class="fw-bold">No tienes turnos ni reservas registrados</h5>
                    <p class="text-muted">Los turnos que solicites con esta cuenta de correo aparecerán detallados aquí.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg mt-2"><i class="fas fa-calendar-plus me-2"></i>Solicitar Turno</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-uppercase small text-muted">
                                <th class="py-3">Código</th>
                                <th class="py-3">Fecha y Hora</th>
                                <th class="py-3">Dependencia / Área</th>
                                <th class="py-3">Trámite / Servicio</th>
                                <th class="py-3">Estado</th>
                                <th class="py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservas as $reserva)
                                <tr>
                                    <td class="py-3">
                                        <strong class="text-primary fs-6">{{ $reserva->codigo }}</strong>
                                        @if($reserva->nombre_institucion)
                                            <span class="d-block small text-muted"><i class="fas fa-university me-1"></i> {{ $reserva->nombre_institucion }}</span>
                                        @elseif($reserva->es_grupal)
                                            <span class="d-block small text-muted"><i class="fas fa-users me-1"></i> Grupal ({{ $reserva->cantidad_personas }} pers.)</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-bold"><i class="far fa-calendar-alt text-primary me-1"></i> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</div>
                                        <small class="text-muted"><i class="far fa-clock me-1"></i> {{ $reserva->hora }} hs</small>
                                    </td>
                                    <td class="py-3">
                                        <span class="fw-semibold">{{ $reserva->turno_horario->turno_tramite->tramite->dependencia->nombre ?? 'Observatorio' }}</span>
                                    </td>
                                    <td class="py-3">
                                        {{ $reserva->turno_horario->turno_tramite->tramite->nombre ?? 'Reserva' }}
                                    </td>
                                    <td class="py-3">
                                        @if($reserva->estado_id == 4)
                                            <span class="badge bg-danger text-white px-3 py-2 rounded-pill"><i class="fas fa-times-circle me-1"></i> Cancelado</span>
                                            @if($reserva->motivo_cancelacion)
                                                <small class="d-block text-muted mt-1" title="{{ $reserva->motivo_cancelacion }}">
                                                    <em>Motivo: {{ \Illuminate\Support\Str::limit($reserva->motivo_cancelacion, 30) }}</em>
                                                </small>
                                            @endif
                                        @elseif($reserva->estado_id == 3)
                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i> Confirmado</span>
                                        @else
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fas fa-clock me-1"></i> Pendiente</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('turnos.print', [$reserva->id]) }}" class="btn btn-outline-danger btn-sm px-3" title="Descargar Comprobante PDF" target="_blank">
                                                <i class="fas fa-file-pdf me-1"></i> Comprobante
                                            </a>
                                            @if($reserva->estado_id != 4)
                                                <button type="button" class="btn btn-outline-warning btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalCancelarReserva_{{ $reserva->id }}" data-toggle="modal" data-target="#modalCancelarReserva_{{ $reserva->id }}" title="Cancelar esta reserva">
                                                    <i class="fas fa-times me-1"></i> Cancelar
                                                </button>
                                            @endif
                                        </div>

                                        @if($reserva->estado_id != 4)
                                            <!-- Modal de Cancelación para este turno -->
                                            <div class="modal fade text-start" id="modalCancelarReserva_{{ $reserva->id }}" tabindex="-1" aria-labelledby="modalLabel_{{ $reserva->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content" style="border-radius: 12px; border: 0;">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title text-white font-weight-bold" id="modalLabel_{{ $reserva->id }}"><i class="fas fa-exclamation-triangle me-1"></i> Cancelar Turno {{ $reserva->codigo }}</h5>
                                                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        {!! Form::open(['route' => ['tramite.cancelar', $reserva->codigo], 'method' => 'post']) !!}
                                                            <div class="modal-body p-4">
                                                                <p class="text-muted small mb-3">
                                                                    Está por cancelar el turno para <strong>{{ $reserva->turno_horario->turno_tramite->tramite->nombre ?? 'Trámite' }}</strong> el día <strong>{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} a las {{ $reserva->hora }} hs</strong>. Esta acción liberará el cupo en el sistema.
                                                                </p>
                                                                <div class="mb-3">
                                                                    <label for="motivo_cancelacion_{{ $reserva->id }}" class="form-label font-weight-bold text-dark">Motivo de Cancelación *</label>
                                                                    <textarea name="motivo_cancelacion" id="motivo_cancelacion_{{ $reserva->id }}" class="form-control" rows="3" placeholder="Ej: Conflicto de horario / Trámite realizado por otro medio..." required style="border-radius: 8px;"></textarea>
                                                                    <small class="text-muted">Describa la razón de la cancelación (mínimo 5 caracteres).</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light">
                                                                <button type="button" class="btn btn-secondary font-weight-bold" data-bs-dismiss="modal" data-dismiss="modal" style="border-radius: 8px;">Cerrar</button>
                                                                <button type="submit" class="btn btn-danger font-weight-bold" style="border-radius: 8px;"><i class="fas fa-check me-1"></i> Confirmar Cancelación</button>
                                                            </div>
                                                        {!! Form::close() !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
