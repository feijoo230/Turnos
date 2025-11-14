<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MesasHabilitadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mesas_habilitadas', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('dependencia_id')->nullable()->unsigned();
            $table->foreign('dependencia_id')->references('id')->on('dependencias');
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
        Schema::dropIfExists('mesas_habilitadas');
    }
}
