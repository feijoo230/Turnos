<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProyectoExtension;
use App\Models\Investigacion;

class ProyectosAndInvestigacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Proyectos de Extensión iniciales
        $proyectos = [
            [
                'nombre' => 'Un cielo en común',
                'subtitulo' => 'Astronomía cultural en Tonco',
                'ano' => '2021',
                'descripcion' => 'El proyecto tuvo como objetivo acercar un telescopio a la comunidad educativa de Tonco, un paraje cercano al Parque Nacional Los Cardones. Pese a las demoras impuestas por la pandemia y el contexto inflacionario, en noviembre de 2021 se concretó la donación de un telescopio refractor al Colegio Secundario Rural de la zona.',
                'imagen' => 'img/observatorio/proyecto-cielo-comun.jpg',
                'enlace_url' => null,
                'orden' => 1,
                'activo' => true,
            ],
            [
                'nombre' => 'La base de los Planetas',
                'subtitulo' => 'Astronomía cultural y capacitación docente en Tonco',
                'ano' => '2023',
                'descripcion' => 'Este proyecto dio continuidad al vínculo con Tonco, avanzando en la construcción del Sendero de los Planetas, un sistema solar a escala entre los cerros. Se construyó la base y columna del Sol y se trazaron los senderos hacia los primeros planetas, completando el trabajo con jornadas de capacitación.',
                'imagen' => 'img/observatorio/proyecto-base-planetas.jpg',
                'enlace_url' => null,
                'orden' => 2,
                'activo' => true,
            ],
            [
                'nombre' => 'Lo aparente y lo real',
                'subtitulo' => 'Astronomía de posición y Didáctica de la Astronomía',
                'ano' => '2023',
                'descripcion' => 'Un curso de extensión destinado a docentes y estudiantes de profesorado, desarrollado en cuatro jornadas intensivas: desde la construcción de un "Aula Celeste" con globo terráqueo paralelo, hasta el uso del simulador Stellarium y una jornada de cierre con observación astronómica.',
                'imagen' => 'img/observatorio/proyecto-aparente-real.jpg',
                'enlace_url' => null,
                'orden' => 3,
                'activo' => true,
            ],
            [
                'nombre' => 'Mi primera Física',
                'subtitulo' => 'Talleres prácticos con simuladores e instrumentos',
                'ano' => '2025',
                'descripcion' => 'Un proyecto pensado para estudiantes de profesorado de educación primaria, que acercó nociones de óptica geométrica y astronomía de posición a futuros docentes de dos institutos de la provincia, explorando cómo estos contenidos pueden adaptarse al aula de nivel primario.',
                'imagen' => 'img/observatorio/proyecto-primera-fisica.jpg',
                'enlace_url' => null,
                'orden' => 4,
                'activo' => true,
            ],
        ];

        foreach ($proyectos as $pData) {
            ProyectoExtension::updateOrCreate(
                ['nombre' => $pData['nombre']],
                $pData
            );
        }

        // 2. Investigaciones y Publicaciones iniciales
        $investigaciones = [
            [
                'titulo' => 'Creación y actividades del Observatorio "Elvio Alanís" de la Universidad Nacional de Salta',
                'revista' => 'Revista de Enseñanza de la Física, Vol. 37.',
                'autores' => 'Equipo Observatorio Alanís',
                'ano' => '2025',
                'descripcion' => 'Una revisión de las etapas fundacionales del Observatorio y sus principales líneas de acción actuales — observaciones, talleres, extensión escolar y colaboraciones institucionales.',
                'enlace_url' => 'https://revistas.unc.edu.ar/index.php/revistaEF/article/view/50844',
                'archivo_pdf' => null,
                'orden' => 1,
                'activo' => true,
            ],
            [
                'titulo' => '¿Qué nos dicen los programas de secundaria sobre la enseñanza de la astronomía en la Provincia de Salta?',
                'revista' => 'Revista de Enseñanza de la Física, Vol. 36.',
                'autores' => 'Equipo Observatorio Alanís',
                'ano' => '2024',
                'descripcion' => 'Un análisis de nueve programas de Física y Astronomía de nivel secundario frente al Diseño Curricular provincial, encontrando que la astrofísica suele ser la gran ausente.',
                'enlace_url' => 'https://revistas.unc.edu.ar/index.php/revistaEF/article/view/47283',
                'archivo_pdf' => null,
                'orden' => 2,
                'activo' => true,
            ],
            [
                'titulo' => 'Secuencia didáctica y orientaciones para el diseño de actividades con el simulador Stellarium',
                'revista' => 'Revista de Enseñanza de la Física, Vol. 35.',
                'autores' => 'Equipo Observatorio Alanís',
                'ano' => '2023',
                'descripcion' => 'Una propuesta paso a paso —preguntas movilizadoras, hipótesis, observación y uso del simulador— para abordar el movimiento aparente del Sol en educación secundaria.',
                'enlace_url' => 'https://revistas.unc.edu.ar/index.php/revistaEF/article/view/43335',
                'archivo_pdf' => null,
                'orden' => 3,
                'activo' => true,
            ],
            [
                'titulo' => 'Pequeñas Historias. Una propuesta para la enseñanza y el aprendizaje de Historia y Epistemología de la Física',
                'revista' => 'Revista de Enseñanza de la Física, Vol. 31.',
                'autores' => 'Equipo Observatorio Alanís',
                'ano' => '2019',
                'descripcion' => 'Una actividad didáctica desarrollada en el Profesorado en Física del Instituto Superior del Profesorado de Salta, acercando a los futuros docentes a la reflexión epistemológica.',
                'enlace_url' => 'https://revistas.unc.edu.ar/index.php/revistaEF/article/view/26650',
                'archivo_pdf' => null,
                'orden' => 4,
                'activo' => true,
            ],
        ];

        foreach ($investigaciones as $iData) {
            Investigacion::updateOrCreate(
                ['titulo' => $iData['titulo']],
                $iData
            );
        }
    }
}
