<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Rol
 * @package App\Models
 * @version October 21, 2016, 8:46 pm UTC
 */
use Carbon\Carbon; 

class Feriado extends Model
{
    public $table = 'feriados';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at', 'fecha'];

    public $fillable = [
        'fecha',
        'observacion',
        'activo'
    ];

    public function getFechaAttribute($value)
{
    return Carbon::parse($value)->format('d/m/Y');
}
}