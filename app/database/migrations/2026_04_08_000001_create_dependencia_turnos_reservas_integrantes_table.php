<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDependenciaTurnosReservasIntegrantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dependencia_turnos_reservas_integrantes', function (Blueprint $table) {
            $table->id();
            $table->integer('reserva_id')->unsigned();
            $table->string('nombre');
            $table->string('apellido')->nullable();
            $table->string('dni')->nullable();
            $table->timestamps();

            $table->foreign('reserva_id')
                  ->references('id')
                  ->on('dependencia_turnos_reservas')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dependencia_turnos_reservas_integrantes');
    }
}
