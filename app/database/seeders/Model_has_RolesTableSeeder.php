<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Model_has_RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('model_has_roles')->insert([
          [
            'role_id' => '1',
            'model_id' => '1',
            'model_type' => 'App\User'
          ],
          [
            'role_id' => '2',
            'model_id' => '2',
            'model_type' => 'App\User'
          ],
          [
            'role_id' => '2',
            'model_id' => '3',
            'model_type' => 'App\User'
          ]
        ]);
    }
}
