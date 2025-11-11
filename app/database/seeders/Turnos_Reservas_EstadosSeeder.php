<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Turnos_Reservas_EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('turnos_reservas_estados')->insert([
          [
            'id' => 1,
            'nombre' => 'Pendiente',
            'activo' => 1
          ],
          [
            'id' => 2,
            'nombre' => 'Finalizado',
            'activo' => 1
          ]
        ]);
    }
}
