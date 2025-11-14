<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('motivo');
            $table->boolean('activo')->nullable();
            
            $table->integer('tramite_id')->nullable()->unsigned();
            $table->foreign('tramite_id')->references('id')->on('tramites');
            
            $table->integer('usuario_origen_id')->nullable()->unsigned();
            $table->foreign('usuario_origen_id')->references('id')->on('users');
            $table->integer('usuario_destino_id')->nullable()->unsigned();
            $table->foreign('usuario_destino_id')->references('id')->on('users');
            $table->integer('dependencia_origen_id')->nullable()->unsigned();
            $table->foreign('dependencia_origen_id')->references('id')->on('dependencias');
            $table->integer('dependencia_destino_id')->nullable()->unsigned();
            $table->foreign('dependencia_destino_id')->references('id')->on('dependencias');

            $table->timestamps();
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
        Schema::dropIfExists('pases');
    }
}
