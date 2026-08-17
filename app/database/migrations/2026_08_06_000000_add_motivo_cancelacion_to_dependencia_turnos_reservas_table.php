<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMotivoCancelacionToDependenciaTurnosReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dependencia_turnos_reservas', function (Blueprint $table) {
            if (!Schema::hasColumn('dependencia_turnos_reservas', 'motivo_cancelacion')) {
                $table->text('motivo_cancelacion')->nullable()->after('estado_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dependencia_turnos_reservas', function (Blueprint $table) {
            if (Schema::hasColumn('dependencia_turnos_reservas', 'motivo_cancelacion')) {
                $table->dropColumn('motivo_cancelacion');
            }
        });
    }
}
