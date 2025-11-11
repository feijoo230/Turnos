<?php

namespace App\Models;

use Eloquent as Model;
use App\User;

/**
 * Class Rol
 * @package App\Models
 * @version October 21, 2016, 8:46 pm UTC
 */
class Turno extends Model
{
    public $table = 'turnos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at', 'fecha_hora_ingreso','fecha_hora_egreso'];

    public $fillable = [
        'fecha_hora_ingreso',
        'fecha_hora_egreso',
        'tiempo_atencion',
        'cliente_id',
        'operador_id',
        'activo',
        'en_curso'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    public function operador()
    {
        return $this->belongsTo(User::class, 'operador_id', 'id');
    }
}
