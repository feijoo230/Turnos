<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoExtension extends Model
{
    use HasFactory;
    
    protected $table = 'proyectos_extension';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'activo'
    ];

    public function turnos_tramites()
    {
        return $this->hasMany(Turnos_Tramites::class, 'proyecto_extension_id');
    }
}
