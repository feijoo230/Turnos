<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Rol
 * @package App\Models
 * @version October 21, 2016, 8:46 pm UTC
 */
class Dependencia_Tramite extends Model
{
    use SoftDeletes;

    public $table = 'dependencia_tramites';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'nombre',
        'dependencia_id',
        'activo',
        'permite_grupal',
        'tipo_modalidad',
        'max_personas_reserva',
        'min_personas_reserva',
        'requiere_institucion',
        'requiere_nomina'
    ];

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_id', 'id');
    }
}
