<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Rol
 * @package App\Models
 * @version October 21, 2016, 8:46 pm UTC
 */
class Turnos_Dependencias extends Model
{
    public $table = 'dependencia_turnos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at', 'fecha_desde', 'fecha_hasta'];

    public $fillable = [
        'intervalo',
        'hora_desde',
        'hora_hasta',
        'fecha_desde',
        'fecha_hasta',
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes',
        'dependencia_id',
        'activo'
    ];

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_id', 'id');
    }

    public function reservas()
    {
        return $this->hasMany(Turnos_Dependencias_Reservas::class, 'dependencia_turno_id', 'id');
    }
}