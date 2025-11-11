<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turnos_Horarios extends Model
{
    use HasFactory;
    protected $table = 'turnos_horarios';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $fillable = [
        'hora_inicio',
        'hora_fin',
        'duracion_minutos',
        'turno_tramite_id',
        'activo',
    ];

    public function turnoTramite()
    {
        return $this->belongsTo(TurnosTramites::class, 'turno_tramite_id');
    }
}
