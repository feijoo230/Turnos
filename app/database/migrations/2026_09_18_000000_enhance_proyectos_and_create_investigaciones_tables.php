<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnhanceProyectosAndCreateInvestigacionesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ampliamos la tabla proyectos_extension
        Schema::table('proyectos_extension', function (Blueprint $table) {
            if (!Schema::hasColumn('proyectos_extension', 'subtitulo')) {
                $table->string('subtitulo')->nullable()->after('nombre');
            }
            if (!Schema::hasColumn('proyectos_extension', 'ano')) {
                $table->string('ano', 50)->nullable()->after('subtitulo');
            }
            if (!Schema::hasColumn('proyectos_extension', 'imagen')) {
                $table->string('imagen')->nullable()->after('descripcion');
            }
            if (!Schema::hasColumn('proyectos_extension', 'enlace_url')) {
                $table->string('enlace_url')->nullable()->after('imagen');
            }
            if (!Schema::hasColumn('proyectos_extension', 'orden')) {
                $table->integer('orden')->default(0)->after('enlace_url');
            }
        });

        // Creamos la tabla investigaciones si no existe
        if (!Schema::hasTable('investigaciones')) {
            Schema::create('investigaciones', function (Blueprint $table) {
                $table->id();
                $table->string('titulo');
                $table->string('revista')->nullable();
                $table->string('autores')->nullable();
                $table->string('ano', 50)->nullable();
                $table->text('descripcion')->nullable();
                $table->string('enlace_url')->nullable();
                $table->string('archivo_pdf')->nullable();
                $table->boolean('activo')->default(true);
                $table->integer('orden')->default(0);
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
        Schema::dropIfExists('investigaciones');

        Schema::table('proyectos_extension', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('proyectos_extension', 'subtitulo')) $columnsToDrop[] = 'subtitulo';
            if (Schema::hasColumn('proyectos_extension', 'ano')) $columnsToDrop[] = 'ano';
            if (Schema::hasColumn('proyectos_extension', 'imagen')) $columnsToDrop[] = 'imagen';
            if (Schema::hasColumn('proyectos_extension', 'enlace_url')) $columnsToDrop[] = 'enlace_url';
            if (Schema::hasColumn('proyectos_extension', 'orden')) $columnsToDrop[] = 'orden';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
}
