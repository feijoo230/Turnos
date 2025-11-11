<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turnos_Tramites extends Model
{
    use HasFactory;
    public $table = 'turnos_tramites';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $dates = ['deleted_at', 'fecha_desde', 'fecha_hasta'];
    
    public $fillable = [
        'fecha_desde',
        'fecha_hasta',
        'dependencia_tramite_id',
        'activo'
    ];
    
    public function tramite()
    {
        return $this->belongsTo(Dependencia_Tramite::class, 'dependencia_tramite_id', 'id');
    }
    public function turnosHorarios()
    {
        return $this->hasMany(Turnos_Horarios::class, 'turno_tramite_id', 'id');
    }
    public function reservas()
    {
        return $this->hasMany(Turnos_Dependencias_Reservas::class, 'dependencia_turno_id', 'id');
    }
}
