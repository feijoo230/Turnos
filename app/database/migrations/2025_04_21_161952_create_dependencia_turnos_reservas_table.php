<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDependenciaTurnosReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dependencia_turnos_reservas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo')->nullable();
            $table->dateTime('fecha_hora');
            $table->date('fecha');
            $table->time('hora');
            $table->string('nombre_apellido');
            $table->string('dni');
            $table->string('celular');
            $table->string('email');

            
            $table->integer('dependencia_turno_id')->nullable()->unsigned();
            $table->foreign('dependencia_turno_id')->references('id')->on('dependencia_turnos');

            $table->integer('dependencia_tramite_id')->nullable()->unsigned();
            $table->foreign('dependencia_tramite_id')->references('id')->on('dependencia_tramites');

            $table->integer('estado_id')->nullable()->unsigned();
            $table->foreign('estado_id')->references('id')->on('turnos_reservas_estados');

            $table->boolean('activo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dependencia_turnos_reservas');
    }
}
