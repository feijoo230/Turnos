<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Rol
 * @package App\Models
 * @version October 21, 2016, 8:46 pm UTC
 */
class Turnos_Dependencias_Reservas extends Model
{
    public $table = 'dependencia_turnos_reservas';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at', 'fecha_hora'];

    public $fillable = [
        'codigo',
        'fecha_hora',
        'fecha',
        'hora',
        'nombre_apellido',
        'dni',
        'celular',
        'email',
        'dependencia_turno_id',
        'dependencia_tramite_id',
        'estado_id',
        'activo'
    ];

    public function turno_dependencia()
    {
        return $this->belongsTo(Turnos_Dependencias::class, 'dependencia_turno_id', 'id');
    }

    public function turno_tramite()
    {
        return $this->belongsTo(Dependencia_Tramite::class, 'dependencia_tramite_id', 'id');
    } 
}