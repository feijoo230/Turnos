<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecurrentesYMetadatosToTurnos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('turnos_tramites', function (Blueprint $table) {
            $table->string('tipo_evento')->nullable();
            $table->string('proyecto_extension')->nullable();
            $table->string('responsable')->nullable();
        });

        Schema::table('turnos_horarios', function (Blueprint $table) {
            $table->boolean('lunes')->default(true);
            $table->boolean('martes')->default(true);
            $table->boolean('miercoles')->default(true);
            $table->boolean('jueves')->default(true);
            $table->boolean('viernes')->default(true);
            $table->boolean('sabado')->default(true);
            $table->boolean('domingo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('turnos_horarios', function (Blueprint $table) {
            $table->dropColumn(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']);
        });

        Schema::table('turnos_tramites', function (Blueprint $table) {
            $table->dropColumn(['tipo_evento', 'proyecto_extension', 'responsable']);
        });
    }
}
