<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dependencias_TramitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dependencia_tramites')->insert([
          [
            'id' => 1,
            'nombre' => 'ALTA DE LEGAJOS',
            'activo' => 1,
            'dependencia_id' => 24,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          [
            'id' => 2,
            'nombre' => 'ACTUALIZACIÓN DE LEGAJOS',
            'activo' => 1,
            'dependencia_id' => 24,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          [
            'id' => 3,
            'nombre' => 'LIQUIDACIÓN DE HABERES',
            'activo' => 1,
            'dependencia_id' => 24,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          [
            'id' => 4,
            'nombre' => 'LICENCIAS Y JUSTIFICACIONES',
            'activo' => 1,
            'dependencia_id' => 24,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          [
            'id' => 5,
            'nombre' => 'SEGUROS Y RETENCIONES',
            'activo' => 1,
            'dependencia_id' => 24,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ]
        ]);
    }
}
