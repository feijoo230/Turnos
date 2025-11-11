<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DependenciasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('dependencias')->insert([
        [
          'id' => '1',
          'nombre' => 'ASAMBLEA UNIVERSITARIA',
          'codigo' => '',
          'parent_id' => 0,
          '_lft' => '1',
          '_rgt' => '44',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '5',
          'nivel' => '1'
        ],
        [
          'id' => '2',
          'nombre' => 'CONSEJO SUPERIOR',
          'codigo' => 'CJOSUP',
          'parent_id' => 1,
          '_lft' => '2',
          '_rgt' => '3',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '5',
          'nivel' => '2'
        ],
        [
          'id' => '3',
          'nombre' => 'RECTORADO',
          'codigo' => 'RECT',
          'parent_id' => 1,
          '_lft' => '4',
          '_rgt' => '21',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '1',
          'nivel' => '2'
        ],
        [
          'id' => '4',
          'nombre' => 'FACULTAD DE CIENCIAS EXACTAS',
          'codigo' => 'FACEXA',
          'parent_id' => 1,
          '_lft' => '22',
          '_rgt' => '23',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '2',
          'nivel' => '2'
        ],
        [
          'id' => '5',
          'nombre' => 'FACULTAD DE INGENIERIA',
          'codigo' => 'FACING',
          'parent_id' => 1,
          '_lft' => '24',
          '_rgt' => '25',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '2',
          'nivel' => '2'
        ],
        [
          'id' => '6',
          'nombre' => 'FACULTAD DE CIENCIAS NATURALES',
          'codigo' => 'FACNAT',
          'parent_id' => 1,
          '_lft' => '26',
          '_rgt' => '27',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '2',
          'nivel' => '2'
        ],
        [
          'id' => '7',
          'nombre' => 'FACULTAD DE CIENCIAS ECONOMICAS',
          'codigo' => 'FACECO',
          'parent_id' => 1,
          '_lft' => '28',
          '_rgt' => '29',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '2',
          'nivel' => '2'
        ],
        [
          'id' => '8',
          'nombre' => 'FACULTAD DE CIENCIAS DE LA SALUD',
          'codigo' => 'FACSAL',
          'parent_id' => 1,
          '_lft' => '30',
          '_rgt' => '31',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '2',
          'nivel' => '2'
        ],
        [
          'id' => '9',
          'nombre' => 'FACULTAD DE HUMANIDADES',
          'codigo' => 'FACHUM',
          'parent_id' => 1,
          '_lft' => '32',
          '_rgt' => '33',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '2',
          'nivel' => '2'
        ],
        [
          'id' => '10',
          'nombre' => 'SEDE REGIONAL TARTAGAL',
          'codigo' => 'SRT',
          'parent_id' => 1,
          '_lft' => '34',
          '_rgt' => '35',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '3',
          'nivel' => '2'
        ],
        [
          'id' => '11',
          'nombre' => 'SEDE REGIONAL ORAN',
          'codigo' => 'SRO',
          'parent_id' => 1,
          '_lft' => '36',
          '_rgt' => '37',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '3',
          'nivel' => '2'
        ],
        [
          'id' => '12',
          'nombre' => 'SEDE REGIONAL SUR (METAN - R° DE LA FRONTERA)',
          'codigo' => 'SRS',
          'parent_id' => 1,
          '_lft' => '38',
          '_rgt' => '39',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '3',
          'nivel' => '2'
        ],
        [
          'id' => '13',
          'nombre' => 'IEM-SALTA',
          'codigo' => 'IEMS',
          'parent_id' => 1,
          '_lft' => '40',
          '_rgt' => '41',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '4',
          'nivel' => '2'
        ],
        [
          'id' => '14',
          'nombre' => 'IEM-TARTAGAL',
          'codigo' => 'IEMT',
          'parent_id' => 1,
          '_lft' => '42',
          '_rgt' => '43',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '4',
          'nivel' => '2'
        ],
        [
          'id' => '15',
          'nombre' => 'CONSEJO DE INVESTIGACIÓN',
          'codigo' => 'CIUNSA',
          'parent_id' => 3,
          '_lft' => '5',
          '_rgt' => '6',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '1',
          'nivel' => '3'
        ],
        [
          'id' => '16',
          'nombre' => 'RADIO',
          'codigo' => 'RADIO',
          'parent_id' => 3,
          '_lft' => '7',
          '_rgt' => '8',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '1',
          'nivel' => '3'
        ],
        [
          'id' => '17',
          'nombre' => 'DIR. DE OBRAS Y SERVICIOS',
          'codigo' => 'DGOYS',
          'parent_id' => 3,
          '_lft' => '9',
          '_rgt' => '10',
          'es_unidad_academica' => '1',
          'tipo_dependencia_id' => '1',
          'nivel' => '3'
        ],
        [
          'id' => '18',
          'nombre' => 'SECRETARIA GENERAL',
          'codigo' => 'SECGRAL',
          'parent_id' => 3,
          '_lft' => '21',
          '_rgt' => '26',
          'es_unidad_academica' => '0',
          'tipo_dependencia_id' => '1',
          'nivel' => '3'
        ],
        [
          'id' => '19',
          'nombre' => 'SECRETARIA DE ASUNTOS JURIDICOS',
          'codigo' => 'SECAJ',
          'parent_id' => 3,
          '_lft' => '27',
          '_rgt' => '28',
          'es_unidad_academica' => '0',
          'tipo_dependencia_id' => '1',
          'nivel' => '3'
        ],
        [
          'id' => '20',
          'nombre' => 'DIRECCION DE COORDINACION ADMINISTRATIVA',
          'codigo' => 'COORDADM',
          'parent_id' => 18,
          '_lft' => '22',
          '_rgt' => '25',
          'es_unidad_academica' => '0',
          'tipo_dependencia_id' => '1',
          'nivel' => '4'
        ],
        [
          'id' => '21',
          'nombre' => 'MESA GRAL. DE ENTRADAS Y SALIDAS',
          'codigo' => 'MGEYS',
          'parent_id' => 20,
          '_lft' => '23',
          '_rgt' => '24',
          'es_unidad_academica' => '0',
          'tipo_dependencia_id' => '1',
          'nivel' => '5'
        ],
        [
          'id' => '22',
          'nombre' => 'DESPACHO DE RECTORADO',
          'codigo' => 'DESREC',
          'parent_id' => 3,
          '_lft' => '11',
          '_rgt' => '12',
          'es_unidad_academica' => '0',
          'tipo_dependencia_id' => '1',
          'nivel' => '3'
        ],
        [
          'id' => '23',
          'nombre' => 'SECRETARIA ADMINISTRATIVA',
          'codigo' => 'SECADM',
          'parent_id' => 3,
          '_lft' => '13',
          '_rgt' => '20',
          'es_unidad_academica' => '0',
          'tipo_dependencia_id' => '1',
          'nivel' => '3'
        ],
        [
          'id' => '24',
          'nombre' => 'DIR. GRAL. DE PERSONAL',
          'codigo' => 'DGP',
          'parent_id' => 23,
          '_lft' => '14',
          '_rgt' => '15',
          'es_unidad_academica' => '0',
          'tipo_dependencia_id' => '1',
          'nivel' => '4'
        ],
        [
          'id' => '25',
          'nombre' => 'DIR. GRAL. DE TESORERIA',
          'codigo' => 'DIRTESO',
          'parent_id' => 23,
          '_lft' => '16',
          '_rgt' => '17',
          'es_unidad_academica' => '0',
          'tipo_dependencia_id' => '1',
          'nivel' => '4'
        ],
        [
          'id' => '26',
          'nombre' => 'DIR. GRAL. DE COMPRAS Y CONTRATACIONES',
          'codigo' => 'DIRCOMPR',
          'parent_id' => 23,
          '_lft' => '18',
          '_rgt' => '19',
          'es_unidad_academica' => '0',
          'tipo_dependencia_id' => '1',
          'nivel' => '4'
        ]
      ]);
    }
}
