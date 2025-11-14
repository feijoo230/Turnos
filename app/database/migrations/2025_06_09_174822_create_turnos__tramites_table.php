<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurnosTramitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnos_tramites', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha_desde')->nullable();
            $table->timestamp('fecha_hasta')->nullable();
            $table->integer('dependencia_tramite_id')->nullable()->unsigned();
            $table->foreign('dependencia_tramite_id')->references('id')->on('dependencia_tramites');
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
        Schema::dropIfExists('turnos_tramites');
    }
}
