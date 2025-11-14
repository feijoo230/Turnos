<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTramites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramites', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('nro_tramite');
            $table->string('asunto');
            $table->text('nota')->nullable();
            $table->string('remitente');
            $table->string('domicilio');
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->boolean('activo')->nullable();

            $table->integer('dependencia_id')->nullable()->unsigned();
            $table->foreign('dependencia_id')->references('id')->on('dependencias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tramites');
    }
}
