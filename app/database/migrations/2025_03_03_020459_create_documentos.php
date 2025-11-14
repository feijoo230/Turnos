<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('vcnombre');
            $table->string('vcnombrefis');
            $table->string('vcext');
            $table->string('mime');
            $table->string('path');
            $table->boolean('activo')->nullable();
            $table->binary('data')->nullable();

            $table->integer('tramite_id')->nullable()->unsigned();
            $table->foreign('tramite_id')->references('id')->on('tramites');

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
        Schema::dropIfExists('documentos');
    }
}
