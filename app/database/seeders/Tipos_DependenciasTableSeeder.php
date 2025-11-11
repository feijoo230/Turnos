<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Tipos_DependenciasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipos_dependencias')->insert([
          [
            'name' => 'RECTORADO',
            'descripcion' => 'web'
          ],
          [
            'name' => 'FACULTADES',
            'descripcion' => 'web'
          ],
          [
            'name' => 'SEDES',
            'descripcion' => 'web'
          ],
          [
            'name' => 'I.E.M.',
            'descripcion' => 'web'
          ],
          [
            'name' => 'OTROS',
            'descripcion' => 'web'
          ]
        ]);
    }
}
