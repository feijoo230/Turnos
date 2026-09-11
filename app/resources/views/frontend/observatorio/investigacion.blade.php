@extends('frontend.layouts.app')

@section('content')
  <div class="page-header text-center">
    <div class="container">
      <span class="section-tag">Publicaciones</span>
      <h2 class="page-title">Investigación</h2>
      <p class="text-muted lead" style="max-width: 800px; margin: 0 auto;">El equipo del Observatorio participa activamente en la investigación sobre enseñanza de la astronomía y la física, publicando sus experiencias y hallazgos en revistas especializadas.</p>
    </div>
  </div>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        
        <div class="card shadow-sm mb-4" style="border: none; border-left: 4px solid var(--accent-blue); border-radius: 8px;">
          <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-start">
            <div class="mr-md-4 mb-3 mb-md-0 text-center text-md-left">
                <i class="fas fa-file-pdf fa-2x" style="color: #ef4444; background: #fee2e2; padding: 15px; border-radius: 8px;"></i>
            </div>
            <div class="flex-grow-1">
                <h4 class="mb-2">Creación y actividades del Observatorio "Elvio Alanís" de la Universidad Nacional de Salta</h4>
                <p class="text-muted mb-2"><strong>Revista de Enseñanza de la Física, Vol. 37.</strong> Una revisión de las etapas fundacionales del Observatorio y sus principales líneas de acción actuales — observaciones, talleres, extensión escolar y colaboraciones institucionales.</p>
                <a href="https://revistas.unc.edu.ar/index.php/revistaEF/article/view/50844" target="_blank" class="text-primary font-weight-bold"><i class="fas fa-external-link-alt mr-1"></i> Ver artículo completo</a>
            </div>
          </div>
        </div>

        <div class="card shadow-sm mb-4" style="border: none; border-left: 4px solid var(--accent-blue); border-radius: 8px;">
          <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-start">
            <div class="mr-md-4 mb-3 mb-md-0 text-center text-md-left">
                <i class="fas fa-file-pdf fa-2x" style="color: #ef4444; background: #fee2e2; padding: 15px; border-radius: 8px;"></i>
            </div>
            <div class="flex-grow-1">
                <h4 class="mb-2">¿Qué nos dicen los programas de secundaria sobre la enseñanza de la astronomía en la Provincia de Salta?</h4>
                <p class="text-muted mb-2"><strong>Revista de Enseñanza de la Física, Vol. 36.</strong> Un análisis de nueve programas de Física y Astronomía de nivel secundario frente al Diseño Curricular provincial, encontrando que la astrofísica suele ser la gran ausente.</p>
                <a href="https://revistas.unc.edu.ar/index.php/revistaEF/article/view/47283" target="_blank" class="text-primary font-weight-bold"><i class="fas fa-external-link-alt mr-1"></i> Ver artículo completo</a>
            </div>
          </div>
        </div>

        <div class="card shadow-sm mb-4" style="border: none; border-left: 4px solid var(--accent-blue); border-radius: 8px;">
          <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-start">
            <div class="mr-md-4 mb-3 mb-md-0 text-center text-md-left">
                <i class="fas fa-file-pdf fa-2x" style="color: #ef4444; background: #fee2e2; padding: 15px; border-radius: 8px;"></i>
            </div>
            <div class="flex-grow-1">
                <h4 class="mb-2">Secuencia didáctica y orientaciones para el diseño de actividades con el simulador Stellarium</h4>
                <p class="text-muted mb-2"><strong>Revista de Enseñanza de la Física, Vol. 35.</strong> Una propuesta paso a paso —preguntas movilizadoras, hipótesis, observación y uso del simulador— para abordar el movimiento aparente del Sol en educación secundaria.</p>
                <a href="https://revistas.unc.edu.ar/index.php/revistaEF/article/view/43335" target="_blank" class="text-primary font-weight-bold"><i class="fas fa-external-link-alt mr-1"></i> Ver artículo completo</a>
            </div>
          </div>
        </div>

        <div class="card shadow-sm mb-4" style="border: none; border-left: 4px solid var(--accent-blue); border-radius: 8px;">
          <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-start">
            <div class="mr-md-4 mb-3 mb-md-0 text-center text-md-left">
                <i class="fas fa-file-pdf fa-2x" style="color: #ef4444; background: #fee2e2; padding: 15px; border-radius: 8px;"></i>
            </div>
            <div class="flex-grow-1">
                <h4 class="mb-2">Pequeñas Historias. Una propuesta para la enseñanza y el aprendizaje de Historia y Epistemología de la Física</h4>
                <p class="text-muted mb-2"><strong>Revista de Enseñanza de la Física, Vol. 31.</strong> Una actividad didáctica desarrollada en el Profesorado en Física del Instituto Superior del Profesorado de Salta, acercando a los futuros docentes a la reflexión epistemológica.</p>
                <a href="https://revistas.unc.edu.ar/index.php/revistaEF/article/view/26650" target="_blank" class="text-primary font-weight-bold"><i class="fas fa-external-link-alt mr-1"></i> Ver artículo completo</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
