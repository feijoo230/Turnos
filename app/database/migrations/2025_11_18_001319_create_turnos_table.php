<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_hora_ingreso')->nullable();
            $table->dateTime('fecha_hora_egreso')->nullable();
            $table->string('tiempo_atencion')->nullable();
            $table->unsignedInteger('cliente_id');
            $table->unsignedInteger('operador_id')->nullable();
            $table->boolean('activo')->nullable()->default(true);
            $table->boolean('en_curso')->nullable()->default(false);
            $table->timestamps();
            $table->softDeletes();

            // The 'users' table uses `int(10) unsigned`, which corresponds to `unsignedInteger`.
            $table->foreign('operador_id')->references('id')->on('users');
            
            // The relationship in the model is to a 'Cliente' model, but there is no 'clientes' table in the SQL dump.
            // There is a 'personas' table. This needs further investigation, so we'll comment out the FK for now.
            // $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turnos');
    }
}
