<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Rol
 * @package App\Models
 * @version October 21, 2016, 8:46 pm UTC
 */
class Turnos_Dependencias_Reservas extends Model
{
    public $table = 'dependencia_turnos_reservas';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at', 'fecha_hora', 'fecha'];

    public $fillable = [
        'codigo',
        'fecha_hora',
        'fecha',
        'hora',
        'nombre_apellido',
        'dni',
        'celular',
        'email',
        'es_grupal',
        'cantidad_personas',
        'nombre_institucion',
        'cargo_responsable',
        'nivel_institucion',
        'cantidad_acompanantes',
        'curso_comision',
        'turno_horario_id',
        'dependencia_tramite_id',
        'estado_id',
        'motivo_cancelacion',
        'activo'
    ];

    public function turno_horario()
    {
        return $this->belongsTo(Turnos_Horarios::class, 'turno_horario_id');
    }

    public function turno_tramite()
    {
        return $this->hasOneThrough(
            Turnos_Tramites::class,
            Turnos_Horarios::class,
            'id', // Foreign key on Turnos_Horarios table
            'id', // Foreign key on Turnos_Tramites table
            'turno_horario_id', // Local key on Turnos_Dependencias_Reservas table
            'turno_tramite_id' // Local key on Turnos_Horarios table
        );
    }

    public function integrantes()
    {
        return $this->hasMany(ReservaIntegrante::class, 'reserva_id');
    }

    /**
     * Genera un código de reserva corto, legible y no secuencial (sin 0/O/1/I/L).
     * Ejemplo: OBS-7K4M9 (32^5 = 33,5 millones de combinaciones posibles).
     */
    public static function generarCodigoUnico(string $prefijo = 'OBS'): string
    {
        $prefijoLimpio = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $prefijo ?: 'OBS'));
        if ($prefijoLimpio === '') {
            $prefijoLimpio = 'OBS';
        }

        $alfabeto = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $maxIndex = strlen($alfabeto) - 1;

        for ($intento = 0; $intento < 20; $intento++) {
            $sufijo = '';
            for ($i = 0; $i < 5; $i++) {
                $sufijo .= $alfabeto[random_int(0, $maxIndex)];
            }
            $candidato = $prefijoLimpio . '-' . $sufijo;

            if (!self::where('codigo', $candidato)->exists()) {
                return $candidato;
            }
        }

        return $prefijoLimpio . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
    }

    public static function normalizarCodigo(?string $codigo): string
    {
        return strtoupper(preg_replace('/\s+/', '', (string) $codigo));
    }

    public static function normalizarDni(?string $dni): string
    {
        $soloDigitos = preg_replace('/\D+/', '', (string) $dni);
        return $soloDigitos !== '' ? $soloDigitos : trim((string) $dni);
    }

    /**
     * Busca una reserva validando coincidencia exacta de Código (insensible a mayúsculas y guiones) y DNI.
     */
    public static function buscarPorCodigoYDni(?string $codigo, ?string $dni): ?self
    {
        $codigoNorm = self::normalizarCodigo($codigo);
        $codigoSinGuion = str_replace('-', '', $codigoNorm);
        $dniNorm = self::normalizarDni($dni);

        if ($codigoSinGuion === '' || $dniNorm === '') {
            return null;
        }

        $candidatos = self::with(['turno_horario.turno_tramite.tramite.dependencia', 'integrantes'])
            ->where(function ($q) use ($codigoNorm, $codigoSinGuion) {
                $q->where('codigo', $codigoNorm)
                  ->orWhereRaw("REPLACE(UPPER(codigo), '-', '') = ?", [$codigoSinGuion]);
            })
            ->get();

        foreach ($candidatos as $reserva) {
            if (self::normalizarDni($reserva->dni) === $dniNorm) {
                return $reserva;
            }
        }

        return null;
    }

    /**
     * Sello corto de verificación de integridad para el comprobante PDF.
     */
    public function codigoVerificacion(): string
    {
        $payload = ($this->id ?? '') . '|' . ($this->codigo ?? '') . '|' . self::normalizarDni($this->dni);
        $hash = strtoupper(hash_hmac('sha256', $payload, (string) config('app.key')));
        return substr($hash, 0, 4) . '-' . substr($hash, 4, 4);
    }
}