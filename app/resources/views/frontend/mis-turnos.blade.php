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
                                <th class="py-3 text-center">Comprobante</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservas as $reserva)
                                <tr>
                                    <td class="py-3"><strong class="text-primary fs-6">{{ $reserva->codigo }}</strong></td>
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
                                        @if($reserva->activo)
                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill">Confirmado</span>
                                        @else
                                            <span class="badge bg-secondary text-white px-3 py-2 rounded-pill">Inactivo / Cancelado</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        <a href="{{ route('turnos.print', [$reserva->id]) }}" class="btn btn-outline-danger btn-sm px-3" title="Descargar Comprobante PDF" target="_blank">
                                            <i class="fas fa-file-pdf me-1"></i> Comprobante
                                        </a>
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
