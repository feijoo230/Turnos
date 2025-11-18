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
        $dependencias = [];
        for ($i = 1; $i <= 26; $i++) {
            $dependencias[] = [
                'usuario_id' => '1',
                'dependencia_id' => (string)$i,
                'activo' => '1'
            ];
        }
        DB::table('usuarios_dependencias')->insert($dependencias);
    }
}
