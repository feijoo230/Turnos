<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDependenciasTramitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dependencia_tramites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->boolean('activo');

            $table->integer('dependencia_id')->nullable()->unsigned();
            $table->foreign('dependencia_id')->references('id')->on('dependencias');

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
        Schema::dropIfExists('dependencia_tramites');
    }
}
