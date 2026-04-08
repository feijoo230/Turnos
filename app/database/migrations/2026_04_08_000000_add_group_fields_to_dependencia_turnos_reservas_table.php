<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupFieldsToDependenciaTurnosReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dependencia_turnos_reservas', function (Blueprint $table) {
            $table->boolean('es_grupal')->default(false)->after('email');
            $table->integer('cantidad_personas')->default(1)->after('es_grupal');
            $table->string('nombre_institucion')->nullable()->after('cantidad_personas');
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
            $table->dropColumn(['es_grupal', 'cantidad_personas', 'nombre_institucion']);
        });
    }
}
