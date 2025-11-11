<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Usuario_Dependencia_OrigenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuario_dependencia_origen')->insert([
          [
            'usuario_id' => '1',
            'dependencia_id' => '21',
            'activo' => '1'
          ]
        ]);
    }
}
