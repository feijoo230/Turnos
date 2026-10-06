<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\User;

class MiembroEquipo extends Model
{
    use HasFactory;

    protected $table = 'equipo_miembros';

    protected $fillable = [
        'user_id',
        'nombre',
        'cargo',
        'tipo',
        'area',
        'email',
        'foto',
        'biografia',
        'orden',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    /**
     * Relación opcional con la cuenta de usuario del sistema (Many2one)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope para docentes responsables
     */
    public function scopeResponsables($query)
    {
        return $query->where('tipo', 'responsable');
    }

    /**
     * Scope para colaboradores
     */
    public function scopeColaboradores($query)
    {
        return $query->where('tipo', 'colaborador');
    }

    /**
     * Scope para miembros activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Accesor para URL de la foto de perfil o avatar
     */
    public function getFotoUrlAttribute()
    {
        if (empty($this->foto)) {
            return null;
        }

        if (Str::startsWith($this->foto, ['http://', 'https://'])) {
            return $this->foto;
        }

        return asset($this->foto);
    }
}
