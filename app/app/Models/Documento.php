<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Rol
 * @package App\Models
 * @version October 21, 2016, 8:46 pm UTC
 */
class Documento extends Model
{
    public $table = 'documentos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'vcnombre',
        'vcnombrefis',
        'vcext',
        'data',
        'tramite_id',
        'mime',
        'path',
        'activo'
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class, 'tramite_id', 'id');
    }
}