<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEvento extends Model
{
    use HasFactory;
    
    protected $table = 'tipos_evento';
    
    protected $fillable = [
        'nombre',
        'activo'
    ];

    public function turnos_tramites()
    {
        return $this->hasMany(Turnos_Tramites::class, 'tipo_evento_id');
    }
}
