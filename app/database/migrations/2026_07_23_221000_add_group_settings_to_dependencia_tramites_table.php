<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupSettingsToDependenciaTramitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dependencia_tramites', function (Blueprint $table) {
            $table->boolean('permite_grupal')->default(false)->after('activo');
            $table->string('tipo_modalidad')->default('individual')->after('permite_grupal'); // 'individual', 'grupal', 'institucional', 'mixto'
            $table->integer('max_personas_reserva')->nullable()->default(10)->after('tipo_modalidad');
            $table->integer('min_personas_reserva')->nullable()->default(1)->after('max_personas_reserva');
            $table->boolean('requiere_institucion')->default(false)->after('min_personas_reserva');
            $table->boolean('requiere_nomina')->default(false)->after('requiere_institucion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dependencia_tramites', function (Blueprint $table) {
            $table->dropColumn([
                'permite_grupal',
                'tipo_modalidad',
                'max_personas_reserva',
                'min_personas_reserva',
                'requiere_institucion',
                'requiere_nomina'
            ]);
        });
    }
}
