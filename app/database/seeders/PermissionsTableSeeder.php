<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
          [
            'name' => 'ver usuario',
            'guard_name' => 'web'
          ],
          [
            'name' => 'crear usuario',
            'guard_name' => 'web'
          ],
          [
            'name' => 'editar usuario',
            'guard_name' => 'web'
          ],
          [
            'name' => 'eliminar usuario',
            'guard_name' => 'web'
          ],
          [
            'name' => 'listar usuario',
            'guard_name' => 'web'
          ],
          [
            'name' => 'administracion',
            'guard_name' => 'web'
          ],
          [
            'name' => 'expedientes',
            'guard_name' => 'web'
          ],
          [
            'name' => 'concursoconsultar',
            'guard_name' => 'web'
          ],
          [
            'name' => 'concursoalta',
            'guard_name' => 'web'
          ],
          [
            'name' => 'isadmin',
            'guard_name' => 'web'
          ],
          [
            'name' => 'isoperador',
            'guard_name' => 'web'
          ]
        ]);
    }
}
