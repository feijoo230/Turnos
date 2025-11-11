<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Rol
 * @package App\Models
 * @version October 21, 2016, 8:46 pm UTC
 */
class Rol extends Model
{
    public $table = 'roles';
    
    const ADMINISTRADOR = '1';
    const DATA_ENTER = '2';
    const CONSULTOR = '3';
}
