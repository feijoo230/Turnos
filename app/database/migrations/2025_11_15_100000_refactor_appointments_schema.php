<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorAppointmentsSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dependencia_turnos_reservas', function (Blueprint $table) {
            $table->unsignedBigInteger('turno_horario_id')->nullable()->after('dependencia_tramite_id');
            
            $table->foreign('turno_horario_id')->references('id')->on('turnos_horarios');

            // Before dropping, we need to drop the foreign key constraint if it exists.
            // The default constraint name is <table>_<column>_foreign.
            // To be safe, we can get the foreign key name from the schema builder.
            // However, for this context, we assume the default name.
            try {
                $table->dropForeign(['dependencia_turno_id']);
            } catch (\Exception $e) {
                // Ignore if it fails, maybe the constraint has a different name or doesn't exist.
            }
            $table->dropColumn('dependencia_turno_id');
        });

        Schema::dropIfExists('dependencia_turnos');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('dependencia_turnos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('intervalo');
            $table->time('hora_desde');
            $table->time('hora_hasta');
            $table->date('fecha_desde');
            $table->date('fecha_hasta');
            $table->boolean('lunes');
            $table->boolean('martes');
            $table->boolean('miercoles');
            $table->boolean('jueves');
            $table->boolean('viernes');
            $table->unsignedBigInteger('dependencia_id');
            $table->boolean('activo');
            $table->timestamps();
        });

        Schema::table('dependencia_turnos_reservas', function (Blueprint $table) {
            $table->unsignedBigInteger('dependencia_turno_id')->nullable();
            // Assuming the new dependencia_turnos table has a bigIncrements id.
            $table->foreign('dependencia_turno_id')->references('id')->on('dependencia_turnos');

            $table->dropForeign(['turno_horario_id']);
            $table->dropColumn('turno_horario_id');
        });
    }
}
