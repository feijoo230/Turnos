<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioDependenciaOrigenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_dependencia_origen', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('activo')->nullable();

            $table->integer('usuario_id')->nullable()->unsigned();
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->integer('dependencia_id')->nullable()->unsigned();
            $table->foreign('dependencia_id')->references('id')->on('dependencias');

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
        Schema::dropIfExists('usuario_dependencia_origen');
    }
}
