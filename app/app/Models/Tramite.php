<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Rol
 * @package App\Models
 * @version October 21, 2016, 8:46 pm UTC
 */
class Tramite extends Model
{
    public $table = 'tramites';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'nro_tramite',
        'asunto',
        'remitente',
        'domicilio',
        'telefono',
        'correo',
        'dependencia_id',
        'activo'
    ];

    public function documentos()
    {
        //return $this->belongsToMany(Documento::class);
        return $this->hasMany(Documento::class);
    }

    public function dependencia()
    {
        //return $this->belongsToMany(Documento::class);
        //return $this->hasMany(Documento::class);
        return $this->belongsTo(Dependencia::class, 'dependencia_id', 'id');
    }
/*
    public static function getTramiteUsuario($usuario_id = null)
    {
        return Tramite::where('dependencia_recibe_id',)->orderBy('created_at', 'desc')
            ->paginate(15);
    }*/
}