<?php

namespace App\Imports;

use App\Models\Feriado;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class FeriadosImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (!isset($row['fecha'])) {
            return null;
        }

        try {
            if (is_numeric($row['fecha'])) {
                $fecha = Carbon::instance(Date::excelToDateTimeObject($row['fecha']));
            } else {
                $fecha = Carbon::parse($row['fecha']);
            }
        } catch (\Exception $e) {
            return null;
        }

        $formatted_date = $fecha->format('Y-m-d');
        $feriado = Feriado::whereDate('fecha', $formatted_date)->first();

        if ($feriado) {
            $feriado->update([
                'observacion' => $row['observacion'] ?? $feriado->observacion,
                'activo' => 1
            ]);
            return null; // Retornamos null porque ya lo actualizamos manualmente
        }

        return new Feriado([
            'fecha'       => $fecha,
            'observacion' => $row['observacion'] ?? 'Importado',
            'activo'      => 1,
        ]);
    }
}
