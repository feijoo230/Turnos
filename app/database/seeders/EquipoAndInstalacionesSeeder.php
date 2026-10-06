<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MiembroEquipo;
use App\Models\Instalacion;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class EquipoAndInstalacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolOperador = Role::where('name', 'OPERADOR')->first();

        // 1. Docentes Responsables
        $adminUser = User::where('email', 'admin@admin.com')->first();

        // Usuario para Carlos Martínez
        $userCarlos = User::firstOrCreate(
            ['email' => 'carlos.martinez@unsa.edu.ar'],
            [
                'name' => 'Carlos Martínez',
                'password' => Hash::make('123456'),
                'activo' => 1
            ]
        );
        if ($rolOperador) {
            $userCarlos->syncRoles(['OPERADOR']);
        }
        $userCarlos->dependencias()->syncWithoutDetaching([27]);

        $responsables = [
            [
                'nombre' => 'Hugo Sebastián Zerpa',
                'cargo' => 'Dirección y Gestión Institucional',
                'tipo' => 'responsable',
                'area' => 'Dirección y Gestión Institucional',
                'email' => 'admin@admin.com',
                'user_id' => $adminUser ? $adminUser->id : null,
                'orden' => 1,
                'activo' => true
            ],
            [
                'nombre' => 'Carlos Martínez',
                'cargo' => 'Gestión Técnica e Investigación',
                'tipo' => 'responsable',
                'area' => 'Gestión Técnica e Investigación',
                'email' => 'carlos.martinez@unsa.edu.ar',
                'user_id' => $userCarlos->id,
                'orden' => 2,
                'activo' => true
            ],
        ];

        foreach ($responsables as $r) {
            MiembroEquipo::updateOrCreate(
                ['nombre' => $r['nombre']],
                $r
            );
        }

        // 2. Colaboradores con sus usuarios como Operadores
        $colaboradores = [
            [
                'nombre_display' => 'Gómez, María José',
                'nombre_user' => 'María José Gómez',
                'email' => 'mjgomez@unsa.edu.ar'
            ],
            [
                'nombre_display' => 'Martin, Marcos',
                'nombre_user' => 'Marcos Martin',
                'email' => 'mmartin@unsa.edu.ar'
            ],
            [
                'nombre_display' => 'Cruz, Bruno',
                'nombre_user' => 'Bruno Cruz',
                'email' => 'bcruz@unsa.edu.ar'
            ],
            [
                'nombre_display' => 'Calderón, Débora',
                'nombre_user' => 'Débora Calderón',
                'email' => 'dcalderon@unsa.edu.ar'
            ],
            [
                'nombre_display' => 'Zerpa, Fabián',
                'nombre_user' => 'Fabián Zerpa',
                'email' => 'fzerpa@unsa.edu.ar'
            ],
            [
                'nombre_display' => 'Maldonado, Cristian',
                'nombre_user' => 'Cristian Maldonado',
                'email' => 'cmaldonado@unsa.edu.ar'
            ],
            [
                'nombre_display' => 'Mirabal, Micaela',
                'nombre_user' => 'Micaela Mirabal',
                'email' => 'mmirabal@unsa.edu.ar'
            ],
            [
                'nombre_display' => 'Díaz, Ana Gabriela',
                'nombre_user' => 'Ana Gabriela Díaz',
                'email' => 'adiaz@unsa.edu.ar'
            ],
            [
                'nombre_display' => 'Ibarra, Janet',
                'nombre_user' => 'Janet Ibarra',
                'email' => 'jibarra@unsa.edu.ar'
            ],
            [
                'nombre_display' => 'Enrique, Candela',
                'nombre_user' => 'Candela Enrique',
                'email' => 'cenrique@unsa.edu.ar'
            ],
            [
                'nombre_display' => 'Flores, Camila Anahí',
                'nombre_user' => 'Camila Anahí Flores',
                'email' => 'cflores@unsa.edu.ar'
            ],
            [
                'nombre_display' => 'Rodríguez, Alfio Antonio',
                'nombre_user' => 'Alfio Antonio Rodríguez',
                'email' => 'arodriguez@unsa.edu.ar'
            ],
            [
                'nombre_display' => 'Cabrera, Julián Nicolás',
                'nombre_user' => 'Julián Nicolás Cabrera',
                'email' => 'jcabrera@unsa.edu.ar'
            ],
            [
                'nombre_display' => 'Pachado, Agustina',
                'nombre_user' => 'Agustina Pachado',
                'email' => 'apachado@unsa.edu.ar'
            ]
        ];

        $ordenColab = 1;
        foreach ($colaboradores as $c) {
            // Crear usuario como operador en el sistema de turnos
            $usuario = User::firstOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['nombre_user'],
                    'password' => Hash::make('123456'),
                    'activo' => 1
                ]
            );

            // Asignar rol de OPERADOR y permisos correspondientes
            if ($rolOperador) {
                $usuario->syncRoles(['OPERADOR']);
            }

            // Asignar dependencia Observatorio (id: 27)
            $usuario->dependencias()->syncWithoutDetaching([27]);

            // Vincular con MiembroEquipo
            MiembroEquipo::updateOrCreate(
                ['nombre' => $c['nombre_display']],
                [
                    'cargo' => null, // Dejamos cargo limpio para evitar "Colaborador / Estudiante" redundante
                    'tipo' => 'colaborador',
                    'area' => 'Gestión, Técnico, Didáctico, Comunicación y Académico',
                    'email' => $c['email'],
                    'user_id' => $usuario->id,
                    'orden' => $ordenColab++,
                    'activo' => true
                ]
            );
        }

        // 3. Instalaciones y Equipamiento
        $instalaciones = [
            [
                'nombre' => 'Cúpula Hemisférica',
                'icono' => 'fas fa-university',
                'descripcion' => 'Construida de forma totalmente artesanal por el equipo fundador del Observatorio. Fue inaugurada el 29 de agosto de 1988 y desde entonces se ha convertido en el ícono arquitectónico que resguarda nuestro instrumento principal.',
                'caracteristicas' => "Montaje artesanal\nEspacio para grupos reducidos\nDiseño hemisférico optimizado",
                'imagen' => 'img/observatorio/instalacion-cupula.jpg',
                'orden' => 1,
                'activo' => true
            ],
            [
                'nombre' => 'Telescopio Reflector',
                'icono' => 'fas fa-telescope',
                'descripcion' => 'Es nuestro instrumento principal. Un telescopio reflector newtoniano de 500 milímetros de diámetro y relación focal f/6. Inaugurado en 1994, permite observaciones de espacio profundo y observación planetaria de alta calidad.',
                'caracteristicas' => "Newtoniano 500 mm\nRelación focal f/6\nIdeal para cielo profundo",
                'imagen' => 'img/observatorio/instalacion-telescopio.png',
                'orden' => 2,
                'activo' => true
            ],
            [
                'nombre' => 'Instrumental Móvil',
                'icono' => 'fas fa-binoculars',
                'descripcion' => "Para las actividades de campo, observaciones masivas y proyectos de extensión como 'Un cielo en común', el Observatorio dispone de telescopios portátiles y binoculares de gran apertura.",
                'caracteristicas' => "Telescopios refractores portátiles\nBinoculares astronómicos\nEquipamiento didáctico auxiliar",
                'imagen' => 'img/observatorio/instalacion-instrumental.png',
                'orden' => 3,
                'activo' => true
            ],
        ];

        foreach ($instalaciones as $inst) {
            Instalacion::updateOrCreate(
                ['nombre' => $inst['nombre']],
                $inst
            );
        }
    }
}
