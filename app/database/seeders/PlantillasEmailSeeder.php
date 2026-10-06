<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlantillaEmail;

class PlantillasEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $templates = PlantillaEmail::getDefaultTemplates();

        foreach ($templates as $clave => $data) {
            PlantillaEmail::updateOrCreate(
                ['clave' => $clave],
                $data
            );
        }
    }
}
