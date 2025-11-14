<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('apellido');
            $table->string('nombre');
            $table->string('nro_documento');
            $table->string('domicilio')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('correo')->nullable();
            $table->string('tel_fijo')->nullable();
            $table->string('tel_movil')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->boolean('activo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
