<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipoAndInstalacionesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('equipo_miembros')) {
            Schema::create('equipo_miembros', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->nullable();
                $table->string('nombre');
                $table->string('cargo')->nullable();
                $table->string('tipo')->default('colaborador'); // 'responsable' o 'colaborador'
                $table->string('area')->nullable(); // Ej: Gestión, Técnico, Didáctico, etc.
                $table->string('email')->nullable();
                $table->string('foto')->nullable();
                $table->text('biografia')->nullable();
                $table->integer('orden')->default(0);
                $table->boolean('activo')->default(true);
                $table->timestamps();

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
            });
        }

        if (!Schema::hasTable('instalaciones')) {
            Schema::create('instalaciones', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nombre');
                $table->string('icono')->nullable()->default('fas fa-university');
                $table->text('descripcion');
                $table->text('caracteristicas')->nullable(); // Viñetas separadas por saltos de línea
                $table->string('imagen')->nullable();
                $table->integer('orden')->default(0);
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instalaciones');
        Schema::dropIfExists('equipo_miembros');
    }
}
