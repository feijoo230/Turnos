<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Administrador',
                'email' => 'admin@admin.com',
                'password' => bcrypt('123456')
            ],
            [
                'id' => 2,
                 'name' => 'Administrador',
                'email' => 'admin2@admin.com',
                'password' => bcrypt('123456')
            ],
        ]);
    }
}
