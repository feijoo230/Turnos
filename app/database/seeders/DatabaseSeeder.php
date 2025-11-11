<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Tipos_DependenciasTableSeeder::class);
        $this->call(DependenciasTableSeeder::class);
    	$this->call(RolesTableSeeder::class);
    	$this->call(PermissionsTableSeeder::class);
    	$this->call(Role_has_PermissionsTableSeeder::class);
    	$this->call(UsersTableSeeder::class);
    	$this->call(Model_has_RolesTableSeeder::class);
        $this->call(Usuarios_DependenciasTableSeeder::class);
        $this->call(Usuario_Dependencia_OrigenTableSeeder::class);
        $this->call(Mesas_HabilitadasTableSeeder::class);
        $this->call(Turnos_Reservas_EstadosSeeder::class);
        $this->call(Dependencias_TramitesSeeder::class);
    }
}
