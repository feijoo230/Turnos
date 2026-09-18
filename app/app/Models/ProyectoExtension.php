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
        'subtitulo',
        'ano',
        'descripcion',
        'imagen',
        'enlace_url',
        'orden',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    public function turnos_tramites()
    {
        return $this->hasMany(Turnos_Tramites::class, 'proyecto_extension_id');
    }

    /**
     * Accesor para obtener la URL pública de la imagen del proyecto
     */
    public function getImagenUrlAttribute()
    {
        if (empty($this->imagen)) {
            return asset('img/observatorio/divulgacion.jpg');
        }

        // Si ya es una URL externa o ruta directa bajo public (ej. img/observatorio/...)
        if (\Illuminate\Support\Str::startsWith($this->imagen, ['http://', 'https://'])) {
            return $this->imagen;
        }

        return asset($this->imagen);
    }
}
