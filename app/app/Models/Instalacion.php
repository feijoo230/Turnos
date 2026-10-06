<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Instalacion extends Model
{
    use HasFactory;

    protected $table = 'instalaciones';

    protected $fillable = [
        'nombre',
        'icono',
        'descripcion',
        'caracteristicas',
        'imagen',
        'orden',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    /**
     * Scope para instalaciones activas
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Accesor para URL de la imagen
     */
    public function getImagenUrlAttribute()
    {
        if (empty($this->imagen)) {
            return asset('img/observatorio/instalacion-cupula.jpg');
        }

        if (Str::startsWith($this->imagen, ['http://', 'https://'])) {
            return $this->imagen;
        }

        return asset($this->imagen);
    }

    /**
     * Accesor que desglosa las características separadas por salto de línea en un array
     */
    public function getCaracteristicasListAttribute()
    {
        if (empty($this->caracteristicas)) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $this->caracteristicas);
        $result = [];
        foreach ($lines as $line) {
            $trimmed = trim($line, " \t\n\r\0\x0B-•*");
            if (!empty($trimmed)) {
                $result[] = $trimmed;
            }
        }

        return $result;
    }
}
