<?php

namespace App\Exports;

use App\Models\Turnos_Dependencias_Reservas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TurnosReservadosExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Turnos_Dependencias_Reservas::with(['dependencia_tramite.dependencia', 'dependencia_tramite.tramite'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha',
            'Hora',
            'Dependencia',
            'Trámite',
            'DNI',
            'Nombre',
            'Email'
        ];
    }

    public function map($reserva): array
    {
        return [
            $reserva->id,
            $reserva->fecha,
            $reserva->hora,
            $reserva->dependencia_tramite->dependencia->nombre ?? 'N/A',
            $reserva->dependencia_tramite->tramite->nombre ?? 'N/A',
            $reserva->dni,
            $reserva->nombre_apellido,
            $reserva->email
        ];
    }
}
