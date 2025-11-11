<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Rol
 * @package App\Models
 * @version October 21, 2016, 8:46 pm UTC
 */
class Persona extends Model
{
    public $table = 'personas';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'apellido',
        'nombre',
        'nro_documento',
        'domicilio',
        'fecha_nacimiento',
        'correo',
        'tel_fijo',
        'tel_movil',
        'activo'
    ];

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'persona_id', 'id');
    }
}
