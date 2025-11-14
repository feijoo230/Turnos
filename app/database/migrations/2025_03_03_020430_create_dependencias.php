<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDependencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dependencias', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('nombre');
            $table->string('codigo')->nullable();
            $table->boolean('activo')->nullable();
            $table->boolean('es_unidad_academica')->nullable();
            $table->integer('nivel')->nullable()->unsigned();
            $table->nestedSet();

            $table->integer('tipo_dependencia_id')->nullable()->unsigned();
            $table->foreign('tipo_dependencia_id')->references('id')->on('tipos_dependencias');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dependencias');
    }
}
