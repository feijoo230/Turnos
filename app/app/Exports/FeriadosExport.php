<?php

namespace App\Exports;

use App\Models\Feriado;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FeriadosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Feriado::all(['fecha', 'observacion', 'activo']);
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Observación',
            'Activo'
        ];
    }
}
