<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreatePlantillasEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('plantillas_email')) {
            Schema::create('plantillas_email', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('clave', 100)->unique();
                $table->string('nombre', 255);
                $table->string('circuito', 50)->default('individual'); // 'individual', 'colegio'
                $table->string('evento', 50)->default('confirmacion');   // 'confirmacion', 'cancelacion', 'solicitud'
                $table->string('asunto', 255);
                $table->longText('cuerpo_html');
                $table->text('descripcion')->nullable();
                $table->text('variables_disponibles')->nullable();
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });
        }

        // Crear permiso y asignarlo al Administrador
        try {
            $permiso = Permission::firstOrCreate(['name' => 'gestionar plantillas email', 'guard_name' => 'web']);
            $rolAdmin = Role::where('name', 'ADMINISTRADOR')->first();
            if ($rolAdmin && !$rolAdmin->hasPermissionTo('gestionar plantillas email')) {
                $rolAdmin->givePermissionTo($permiso);
            }
        } catch (\Exception $e) {
            \Log::warning('No se pudo registrar o asignar el permiso gestionar plantillas email: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plantillas_email');

        try {
            $permiso = Permission::where('name', 'gestionar plantillas email')->first();
            if ($permiso) {
                $permiso->delete();
            }
        } catch (\Exception $e) {
            // Ignorar en rollback si la tabla no existe
        }
    }
}
