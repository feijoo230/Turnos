<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Rol
 * @package App\Models
 * @version October 21, 2016, 8:46 pm UTC
 */
class Pases extends Model
{
    public $table = 'pases';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'motivo',
        'tramite_id',
        'usuario_origen_id',
        'usuario_destino_id',
        'dependencia_origen_id',
        'dependencia_destino_id'
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class, 'tramite_id', 'id');
    }
}