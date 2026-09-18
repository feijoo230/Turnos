<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Investigacion extends Model
{
    use HasFactory;

    protected $table = 'investigaciones';

    protected $fillable = [
        'titulo',
        'revista',
        'autores',
        'ano',
        'descripcion',
        'enlace_url',
        'archivo_pdf',
        'activo',
        'orden'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    /**
     * Accesor para obtener la URL pública del documento PDF si existe
     */
    public function getPdfUrlAttribute()
    {
        if (empty($this->archivo_pdf)) {
            return null;
        }

        if (Str::startsWith($this->archivo_pdf, ['http://', 'https://'])) {
            return $this->archivo_pdf;
        }

        return asset($this->archivo_pdf);
    }

    /**
     * Accesor que devuelve el mejor enlace disponible (PDF local o URL externa)
     */
    public function getEnlaceFinalAttribute()
    {
        if (!empty($this->pdf_url)) {
            return $this->pdf_url;
        }
        return $this->enlace_url;
    }
}
