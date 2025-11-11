<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Mesas_HabilitadasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mesas_habilitadas')->insert([
            [
                'id' => 1,
                'dependencia_id' => 21,
                'activo' => 1
            ],
            [
                'id' => 2,
                'dependencia_id' => 18,
                'activo' => 1
            ],
            [
                'id' => 3,
                'dependencia_id' => 19,
                'activo' => 1
            ],
            [
                'id' => 4,
                'dependencia_id' => 22,
                'activo' => 1
            ],
            [
                'id' => 5,
                'dependencia_id' => 23,
                'activo' => 1
            ],
            [
                'id' => 6,
                'dependencia_id' => 24,
                'activo' => 1
            ]
        ]);
    }
}
