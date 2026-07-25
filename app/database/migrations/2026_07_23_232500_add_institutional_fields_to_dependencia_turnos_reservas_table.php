<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInstitutionalFieldsToDependenciaTurnosReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dependencia_turnos_reservas', function (Blueprint $table) {
            if (!Schema::hasColumn('dependencia_turnos_reservas', 'cargo_responsable')) {
                $table->string('cargo_responsable')->nullable()->after('nombre_institucion');
            }
            if (!Schema::hasColumn('dependencia_turnos_reservas', 'nivel_institucion')) {
                $table->string('nivel_institucion')->nullable()->after('cargo_responsable');
            }
            if (!Schema::hasColumn('dependencia_turnos_reservas', 'cantidad_acompanantes')) {
                $table->integer('cantidad_acompanantes')->default(0)->nullable()->after('nivel_institucion');
            }
            if (!Schema::hasColumn('dependencia_turnos_reservas', 'curso_comision')) {
                $table->string('curso_comision')->nullable()->after('cantidad_acompanantes');
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
            $table->dropColumn(['cargo_responsable', 'nivel_institucion', 'cantidad_acompanantes', 'curso_comision']);
        });
    }
}
