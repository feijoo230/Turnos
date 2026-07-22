<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProyectosAndTiposTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos_extension', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('tipos_evento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Ensure columns don't exist before adding them just in case
        if (Schema::hasColumn('turnos_tramites', 'tipo_evento_id')) {
            Schema::table('turnos_tramites', function (Blueprint $table) {
                $table->dropColumn(['tipo_evento_id', 'proyecto_extension_id', 'responsable_id']);
            });
        }

        Schema::table('turnos_tramites', function (Blueprint $table) {
            // Drop old string columns
            if (Schema::hasColumn('turnos_tramites', 'tipo_evento')) {
                $table->dropColumn(['tipo_evento', 'proyecto_extension', 'responsable']);
            }
            
            // Add foreign key columns
            $table->unsignedBigInteger('tipo_evento_id')->nullable();
            $table->unsignedBigInteger('proyecto_extension_id')->nullable();
            $table->unsignedInteger('responsable_id')->nullable();

            $table->foreign('tipo_evento_id')->references('id')->on('tipos_evento');
            $table->foreign('proyecto_extension_id')->references('id')->on('proyectos_extension');
            // assuming 'users' table has 'id' column
            $table->foreign('responsable_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('turnos_tramites', function (Blueprint $table) {
            $table->dropForeign(['tipo_evento_id']);
            $table->dropForeign(['proyecto_extension_id']);
            $table->dropForeign(['responsable_id']);
            
            $table->dropColumn(['tipo_evento_id', 'proyecto_extension_id', 'responsable_id']);
            
            $table->string('tipo_evento')->nullable();
            $table->string('proyecto_extension')->nullable();
            $table->string('responsable')->nullable();
        });

        Schema::dropIfExists('tipos_evento');
        Schema::dropIfExists('proyectos_extension');
    }
}
