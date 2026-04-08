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
        $reservas = Turnos_Dependencias_Reservas::with(['turno_tramite.tramite.dependencia', 'integrantes'])->get();
        $data = collect();

        foreach ($reservas as $reserva) {
            if ($reserva->es_grupal && $reserva->integrantes->count() > 0) {
                foreach ($reserva->integrantes as $integrante) {
                    // Clonamos la información para cada integrante
                    $data->push([
                        'reserva' => $reserva,
                        'integrante_nombre' => $integrante->nombre,
                        'integrante_apellido' => $integrante->apellido,
                        'integrante_dni' => $integrante->dni,
                    ]);
                }
            } else {
                // Reserva individual o grupal sin lista cargada
                $data->push([
                    'reserva' => $reserva,
                    'integrante_nombre' => '-',
                    'integrante_apellido' => '-',
                    'integrante_dni' => '-',
                ]);
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'ID Reserva',
            'Código',
            'Fecha',
            'Hora',
            'Dependencia',
            'Trámite',
            'Responsable (Nombre)',
            'Responsable (DNI)',
            'Email',
            'Institución',
            'Total Personas Grupo',
            'Integrante (Nombre)',
            'Integrante (Apellido)',
            'Integrante (DNI)'
        ];
    }

    public function map($row): array
    {
        $reserva = $row['reserva'];
        
        return [
            $reserva->id,
            $reserva->codigo,
            $reserva->fecha->format('d/m/Y'),
            $reserva->hora,
            $reserva->turno_tramite->tramite->dependencia->nombre ?? 'N/A',
            $reserva->turno_tramite->tramite->nombre ?? 'N/A',
            $reserva->nombre_apellido,
            $reserva->dni,
            $reserva->email,
            $reserva->nombre_institucion ?? '-',
            $reserva->cantidad_personas,
            $row['integrante_nombre'],
            $row['integrante_apellido'],
            $row['integrante_dni']
        ];
    }
}
