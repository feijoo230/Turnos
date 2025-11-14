<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->updateOrInsert(['id' => 1],
          [
            'name' => 'ADMINISTRADOR',
            'guard_name' => 'web'
          ]);
        DB::table('roles')->updateOrInsert(['id' => 2],
          [
            'name' => 'OPERADOR',
            'guard_name' => 'web'
          ]);
        DB::table('roles')->updateOrInsert(['id' => 3],
          [
            'name' => 'USUARIO',
            'guard_name' => 'web'
          ]);
    }
}
