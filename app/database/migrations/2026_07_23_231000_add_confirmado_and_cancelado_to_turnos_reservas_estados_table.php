<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddConfirmadoAndCanceladoToTurnosReservasEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $estados = [
            ['id' => 1, 'nombre' => 'Pendiente', 'activo' => 1],
            ['id' => 2, 'nombre' => 'Finalizado', 'activo' => 1],
            ['id' => 3, 'nombre' => 'Confirmado', 'activo' => 1],
            ['id' => 4, 'nombre' => 'Cancelado', 'activo' => 1],
        ];

        foreach ($estados as $estado) {
            DB::table('turnos_reservas_estados')->updateOrInsert(
                ['id' => $estado['id']],
                ['nombre' => $estado['nombre'], 'activo' => $estado['activo']]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('turnos_reservas_estados')->whereIn('id', [3, 4])->delete();
    }
}
