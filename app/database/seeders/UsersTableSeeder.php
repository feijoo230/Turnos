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
                'name' => 'Duran Francisco Javier',
                'email' => 'javdu0113@gmail.com',
                'password' => bcrypt('123456')
            ],
            [
                'id' => 2,
                'name' => 'Duran Francisco Javier',
                'email' => 'javdu1301@gmail.com',
                'password' => bcrypt('123456')
            ],
            [
                'id' => 3,
                'name' => 'Jorge Nina',
                'email' => 'jninajr@gmail.com',
                'password' => bcrypt('123456')
            ]
        ]);
    }
}
