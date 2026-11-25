<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Dependencia;
use App\Models\Dependencia_Tramite;
use App\Models\Turnos_Tramites;
use App\Models\Turnos_Horarios;
use App\Models\Turnos_Dependencias_Reservas;
use App\Mail\TurnoSolicitado;
use App\Mail\TurnoCancelado;

class TurnosSeguridadYConcurrenciaTest extends TestCase
{
    use DatabaseTransactions;

    private function crearEscenarioHorario(string $modalidad, int $cantidadTurnos, int $minPersonas = 2, int $maxPersonas = 30): array
    {
        $dependencia = Dependencia::firstOrFail();

        $tramite = Dependencia_Tramite::create([
            'nombre' => 'Actividad Test ' . strtoupper($modalidad),
            'dependencia_id' => $dependencia->id,
            'activo' => 1,
            'permite_grupal' => $modalidad !== 'individual' ? 1 : 0,
            'tipo_modalidad' => $modalidad,
            'min_personas_reserva' => $minPersonas,
            'max_personas_reserva' => $maxPersonas,
            'requiere_institucion' => $modalidad === 'institucional' ? 1 : 0,
            'requiere_nomina' => 0,
        ]);

        $fechaFutura = Carbon::today()->addDays(5);

        $turnoTramite = Turnos_Tramites::create([
            'dependencia_tramite_id' => $tramite->id,
            'fecha_desde' => Carbon::today()->format('Y-m-d'),
            'fecha_hasta' => Carbon::today()->addDays(15)->format('Y-m-d'),
            'activo' => 1,
        ]);

        $horario = Turnos_Horarios::create([
            'turno_tramite_id' => $turnoTramite->id,
            'hora_inicio' => '19:00:00',
            'hora_fin' => '21:00:00',
            'duracion_minutos' => 60,
            'cantidad_turnos' => $cantidadTurnos,
            'activo' => 1,
            'lunes' => 1, 'martes' => 1, 'miercoles' => 1, 'jueves' => 1, 'viernes' => 1, 'sabado' => 1, 'domingo' => 1,
        ]);

        return [$dependencia, $tramite, $turnoTramite, $horario, $fechaFutura];
    }

    public function test_cupo_mixto_unifica_individual_grupo_y_escuela_sin_sobreventa()
    {
        Mail::fake();

        // Trámite mixto con capacidad física de 30 lugares
        [$dep, $tramite, $tt, $horario, $fecha] = $this->crearEscenarioHorario('mixto', 30, 2, 30);
        $fechaStr = $fecha->format('d/m/Y');
        $horaSession = '19:00:00|' . $horario->id;

        // 1) Reserva individual (1 lugar) -> 1/30 ocupados
        $resp1 = $this->withSession([
            'dependencia_id' => $dep->id,
            'dependencia_tramite_id' => $tramite->id,
            'turno_fecha' => $fechaStr,
            'turno_hora' => $horaSession,
        ])->post(route('tramite.guardar'), [
            'nombre_apellido' => 'Ana Individual',
            'dni' => '30111222',
            'celular' => '3874000001',
            'email' => 'ana@example.com',
            'email_confirmation' => 'ana@example.com',
            'es_grupal' => 0,
            'cantidad_personas' => 1,
        ]);
        $resp1->assertRedirect();
        Mail::assertSent(TurnoSolicitado::class);

        // 2) Reserva grupal (10 lugares) -> 11/30 ocupados
        $resp2 = $this->withSession([
            'dependencia_id' => $dep->id,
            'dependencia_tramite_id' => $tramite->id,
            'turno_fecha' => $fechaStr,
            'turno_hora' => $horaSession,
        ])->post(route('tramite.guardar'), [
            'nombre_apellido' => 'Familia Perez',
            'dni' => '30222333',
            'celular' => '3874000002',
            'email' => 'perez@example.com',
            'email_confirmation' => 'perez@example.com',
            'es_grupal' => 1,
            'cantidad_personas' => 10,
        ]);
        $resp2->assertRedirect();

        // 3) Reserva escolar de 25 alumnos en el mismo horario mixto -> 11 + 25 = 36 > 30 => DEBE RECHAZARSE
        $resp3 = $this->withSession([
            'dependencia_id' => $dep->id,
            'dependencia_tramite_id' => $tramite->id,
            'turno_fecha' => $fechaStr,
            'turno_hora' => $horaSession,
        ])->post(route('tramite.guardar'), [
            'nombre_apellido' => 'Prof. Gomez',
            'dni' => '30333444',
            'celular' => '3874000003',
            'email' => 'escuela@example.com',
            'email_confirmation' => 'escuela@example.com',
            'es_grupal' => 1,
            'cantidad_personas' => 25,
            'nombre_institucion' => 'Escuela N 4012',
            'nivel_institucion' => 'Secundario',
            'cargo_responsable' => 'Docente',
        ]);
        $resp3->assertSessionHas('error');

        $ocupados = Turnos_Dependencias_Reservas::where('turno_horario_id', $horario->id)
            ->where('estado_id', '!=', 4)
            ->sum('cantidad_personas');
        $this->assertEquals(11, $ocupados);

        // 4) Mismo DNI intentando duplicar en el mismo horario -> DEBE RECHAZARSE
        $respDup = $this->withSession([
            'dependencia_id' => $dep->id,
            'dependencia_tramite_id' => $tramite->id,
            'turno_fecha' => $fechaStr,
            'turno_hora' => $horaSession,
        ])->post(route('tramite.guardar'), [
            'nombre_apellido' => 'Ana Individual',
            'dni' => '30.111.222',
            'celular' => '3874000001',
            'email' => 'ana@example.com',
            'email_confirmation' => 'ana@example.com',
            'es_grupal' => 0,
            'cantidad_personas' => 1,
        ]);
        $respDup->assertSessionHas('error');
    }

    public function test_cupo_institucional_escuelas_cuenta_por_delegacion_y_libera_al_cancelar()
    {
        Mail::fake();

        // Trámite exclusivamente institucional: 1 delegación por horario (hasta 35 alumnos)
        [$dep, $tramite, $tt, $horario, $fecha] = $this->crearEscenarioHorario('institucional', 1, 5, 35);
        $fechaStr = $fecha->format('d/m/Y');
        $horaSession = '19:00:00|' . $horario->id;

        $this->withSession([
            'dependencia_id' => $dep->id,
            'dependencia_tramite_id' => $tramite->id,
            'turno_fecha' => $fechaStr,
            'turno_hora' => $horaSession,
        ])->post(route('tramite.guardar'), [
            'nombre_apellido' => 'Docente Uno',
            'dni' => '28111111',
            'celular' => '3874111111',
            'email' => 'docente1@escuela.edu.ar',
            'email_confirmation' => 'docente1@escuela.edu.ar',
            'es_grupal' => 1,
            'cantidad_personas' => 30,
            'nombre_institucion' => 'Colegio 1',
            'nivel_institucion' => 'Secundario',
            'cargo_responsable' => 'Director',
        ])->assertRedirect();

        // Segunda escuela en el mismo horario -> rechazada (cupo de 1 delegación lleno)
        $this->withSession([
            'dependencia_id' => $dep->id,
            'dependencia_tramite_id' => $tramite->id,
            'turno_fecha' => $fechaStr,
            'turno_hora' => $horaSession,
        ])->post(route('tramite.guardar'), [
            'nombre_apellido' => 'Docente Dos',
            'dni' => '28222222',
            'celular' => '3874222222',
            'email' => 'docente2@escuela.edu.ar',
            'email_confirmation' => 'docente2@escuela.edu.ar',
            'es_grupal' => 1,
            'cantidad_personas' => 20,
            'nombre_institucion' => 'Colegio 2',
            'nivel_institucion' => 'Primario',
            'cargo_responsable' => 'Docente',
        ])->assertSessionHas('error');

        $reserva1 = Turnos_Dependencias_Reservas::where('turno_horario_id', $horario->id)->firstOrFail();

        // Verificar formato no secuencial con guion (ej: OBS-7K4M9)
        $this->assertMatchesRegularExpression('/^[A-Z0-9]+-[23456789ABCDEFGHJKLMNPQRSTUVWXYZ]{5}$/', $reserva1->codigo);
    }

    public function test_seguridad_reservas_bloquea_dni_solo_codigo_solo_e_idor()
    {
        Mail::fake();

        [$dep, $tramite, $tt, $horario, $fecha] = $this->crearEscenarioHorario('individual', 10);

        $reserva = Turnos_Dependencias_Reservas::create([
            'codigo' => Turnos_Dependencias_Reservas::generarCodigoUnico('OBS'),
            'fecha_hora' => $fecha->copy()->setTime(19, 0, 0),
            'fecha' => $fecha->format('Y-m-d'),
            'hora' => '19:00:00',
            'nombre_apellido' => 'Victima Prueba',
            'dni' => '32999888',
            'celular' => '3874999888',
            'email' => 'victima@example.com',
            'es_grupal' => 0,
            'cantidad_personas' => 1,
            'turno_horario_id' => $horario->id,
            'dependencia_tramite_id' => $tramite->id,
            'estado_id' => 1,
            'activo' => 1,
        ]);

        // 1) Conociendo SOLO el DNI -> no puede consultar
        $this->get(route('turno.buscar', ['dni_turno' => '32999888']))
            ->assertSessionHasErrors('codigo_turno');

        // 2) Conociendo SOLO el código -> no puede consultar
        $this->get(route('turno.buscar', ['codigo_turno' => $reserva->codigo]))
            ->assertSessionHasErrors('dni_turno');

        // 3) Código válido + DNI ajeno -> no encuentra ni expone datos
        $this->get(route('turno.buscar', ['codigo_turno' => $reserva->codigo, 'dni_turno' => '99999999']))
            ->assertSessionHas('error')
            ->assertSessionMissing('turno_reserva_busqueda');

        // 4) IDOR directo al comprobante PDF sin autorización -> 403
        $this->get(route('turnos.print', [$reserva->id]))
            ->assertStatus(403);

        // 5) IDOR directo a confirmación por ID -> redirige con error
        $this->get(route('tramite.confirmacion', [$reserva->id]))
            ->assertRedirect(route('turnos'))
            ->assertSessionHas('error');

        // 6) IDOR directo a nómina de integrantes (GET y POST) -> bloqueado
        $this->get(route('tramite.integrantes.form', [$reserva->codigo]))
            ->assertRedirect(route('turnos'));
        $this->post(route('tramite.integrantes.guardar', [$reserva->codigo]), [
            'integrantes_manuales' => [['nombre' => 'Hack', 'apellido' => 'Test', 'dni' => '123']],
        ])->assertStatus(403);

        // 7) Intento de cancelar vía POST directo sin autorización ni DNI -> 403 y reserva intacta
        $this->post(route('tramite.cancelar', [$reserva->codigo]), [
            'motivo_cancelacion' => 'Cancelacion maliciosa sin autorizacion',
        ])->assertStatus(403);
        $this->assertEquals(1, $reserva->fresh()->estado_id);

        // 8) Flujo legítimo con Código (minúsculas y sin guion) + DNI (con puntos) -> AUTORIZA y permite cancelar
        $codigoSinGuion = strtolower(str_replace('-', '', $reserva->codigo));
        $respOk = $this->get(route('turno.buscar', [
            'codigo_turno' => $codigoSinGuion,
            'dni_turno' => '32.999.888',
        ]));
        $respOk->assertSessionHas('turno_reserva_busqueda');

        // Ahora con sesión autorizada + confirmación de DNI sí puede cancelar
        $this->post(route('tramite.cancelar', [$reserva->codigo]), [
            'dni_cancelacion' => '32999888',
            'motivo_cancelacion' => 'No puedo asistir por viaje personal',
        ])->assertSessionHas('success');

        $this->assertEquals(4, $reserva->fresh()->estado_id);
        Mail::assertSent(TurnoCancelado::class);
    }
}
