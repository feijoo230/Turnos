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

    protected $dates = ['deleted_at', 'fecha_hora', 'fecha'];

    public $fillable = [
        'codigo',
        'fecha_hora',
        'fecha',
        'hora',
        'nombre_apellido',
        'dni',
        'celular',
        'email',
        'es_grupal',
        'cantidad_personas',
        'nombre_institucion',
        'turno_horario_id',
        'dependencia_tramite_id',
        'estado_id',
        'activo'
    ];

    public function turno_horario()
    {
        return $this->belongsTo(Turnos_Horarios::class, 'turno_horario_id');
    }

    public function turno_tramite()
    {
        return $this->hasOneThrough(
            Turnos_Tramites::class,
            Turnos_Horarios::class,
            'id', // Foreign key on Turnos_Horarios table
            'id', // Foreign key on Turnos_Tramites table
            'turno_horario_id', // Local key on Turnos_Dependencias_Reservas table
            'turno_tramite_id' // Local key on Turnos_Horarios table
        );
    }

    public function integrantes()
    {
        return $this->hasMany(ReservaIntegrante::class, 'reserva_id');
    }
}