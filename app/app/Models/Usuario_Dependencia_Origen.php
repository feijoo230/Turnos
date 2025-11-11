<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Rol
 * @package App\Models
 * @version October 21, 2016, 8:46 pm UTC
 */
class Usuario_Dependencia_Origen extends Model
{
    public $table = 'usuario_dependencia_origen';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'usuario_id',
        'dependencia_id',
        'activo'
    ];
}
