<?php

namespace App\Imports;

use App\Models\ReservaIntegrante;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IntegrantesImport implements ToModel, WithHeadingRow
{
    private $reserva_id;

    public function __construct($reserva_id)
    {
        $this->reserva_id = $reserva_id;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Validar que al menos el nombre no esté vacío
        if (!isset($row['nombre']) || empty($row['nombre'])) {
            return null;
        }

        return new ReservaIntegrante([
            'reserva_id' => $this->reserva_id,
            'nombre'     => $row['nombre'],
            'apellido'   => $row['apellido'] ?? null,
            'dni'        => $row['dni'] ?? null,
        ]);
    }
}
