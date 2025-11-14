<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDependenciaTurnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dependencia_turnos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('intervalo');
            $table->time("hora_desde");
            $table->time("hora_hasta");
            $table->timestamp('fecha_desde')->nullable();
            $table->timestamp('fecha_hasta')->nullable();
            $table->boolean('lunes');
            $table->boolean('martes');
            $table->boolean('miercoles');
            $table->boolean('jueves');
            $table->boolean('viernes');
            
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
        Schema::dropIfExists('dependencia_turnos');
    }
}
