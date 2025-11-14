<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Usuarios_DependenciasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuarios_dependencias')->insert([
          [
            'usuario_id' => '1',
            'dependencia_id' => '18',
            'activo' => '1'
          ],
          [
            'usuario_id' => '1',
            'dependencia_id' => '19',
            'activo' => '1'
          ],
          [
            'usuario_id' => '1',
            'dependencia_id' => '21',
            'activo' => '1'
          ],
          [
            'usuario_id' => '2',
            'dependencia_id' => '24',
            'activo' => '1'
          ]
        ]);
    }
}
