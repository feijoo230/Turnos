<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservaIntegrante extends Model
{
    public $table = 'dependencia_turnos_reservas_integrantes';

    public $fillable = [
        'reserva_id',
        'nombre',
        'apellido',
        'dni'
    ];

    public function reserva()
    {
        return $this->belongsTo(Turnos_Dependencias_Reservas::class, 'reserva_id');
    }
}
