<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Rol
 * @package App\Models
 * @version October 21, 2016, 8:46 pm UTC
 */
class Usuario extends Model
{
    public $table = 'users';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'email',
        'password',
        'activo'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id');
    }
    public function dependencias()
    {
        return $this->belongsToMany(Dependencia::class, 'usuarios_dependencias', 'usuario_id', 'dependencia_id');
    }
    public function dependencias_origen()
    {
        return $this->belongsToMany(Dependencia::class, 'usuario_dependencia_origen', 'usuario_id', 'dependencia_id');
    }
}
