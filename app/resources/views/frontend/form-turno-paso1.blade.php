@extends('layouts.frontend')
@section('content')

<style>
	.paso1-section-header {
		display: flex;
		align-items: center;
		gap: 10px;
		margin-bottom: 14px;
	}
	.paso1-step-num {
		width: 30px;
		height: 30px;
		min-width: 30px;
		border-radius: 50%;
		background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
		color: #ffffff;
		font-weight: 700;
		font-size: 0.9rem;
		display: inline-flex;
		align-items: center;
		justify-content: center;
	}
	.paso1-step-num.muted {
		background: #cbd5e1;
		color: #475569;
	}
	.select-card-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
		gap: 14px;
	}
	.select-card {
		position: relative;
		background: #ffffff;
		border: 2px solid #e2e8f0;
		border-radius: 14px;
		padding: 18px 18px 16px;
		text-align: left;
		cursor: pointer;
		transition: all 0.2s ease;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		min-height: 118px;
		width: 100%;
	}
	.select-card:hover:not(.is-disabled) {
		border-color: #38bdf8;
		background: #f8fafc;
		transform: translateY(-2px);
		box-shadow: 0 8px 20px rgba(2, 132, 199, 0.12);
	}
	.select-card.is-selected {
		border-color: #0284c7;
		background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
		box-shadow: 0 8px 22px rgba(2, 132, 199, 0.18);
	}
	.select-card.is-disabled {
		background: #f8fafc;
		border-color: #e2e8f0;
		border-style: dashed;
		opacity: 0.68;
		cursor: not-allowed;
	}
	.select-card-top {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 10px;
		margin-bottom: 10px;
	}
	.select-card-icon {
		width: 40px;
		height: 40px;
		min-width: 40px;
		border-radius: 10px;
		background: #e0f2fe;
		color: #0284c7;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 1.1rem;
	}
	.select-card.is-selected .select-card-icon {
		background: #0284c7;
		color: #ffffff;
	}
	.select-card.is-disabled .select-card-icon {
		background: #e2e8f0;
		color: #94a3b8;
	}
	.select-card-check {
		font-size: 1.25rem;
		color: #059669;
		opacity: 0;
		transform: scale(0.8);
		transition: all 0.2s ease;
	}
	.select-card.is-selected .select-card-check {
		opacity: 1;
		transform: scale(1);
	}
	.select-card-title {
		font-weight: 700;
		font-size: 1.03rem;
		color: #0f172a;
		line-height: 1.3;
		margin-bottom: 6px;
	}
	.select-card-desc {
		font-size: 0.85rem;
		color: #64748b;
		line-height: 1.4;
		margin-bottom: 10px;
	}
	.select-card-badges {
		display: flex;
		flex-wrap: wrap;
		gap: 6px;
		margin-top: auto;
	}
	.pill-badge {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		font-size: 0.76rem;
		font-weight: 600;
		padding: 4px 10px;
		border-radius: 20px;
	}
	.pill-available {
		background: #dcfce7;
		color: #166534;
	}
	.pill-unavailable {
		background: #f1f5f9;
		color: #64748b;
	}
	.pill-mod-individual {
		background: #e0e7ff;
		color: #3730a3;
	}
	.pill-mod-grupal {
		background: #e0f2fe;
		color: #075985;
	}
	.pill-mod-institucional {
		background: #fef3c7;
		color: #92400e;
	}
	.pill-mod-mixto {
		background: #f3e8ff;
		color: #6b21a8;
	}
	.selection-summary-bar {
		background: #f8fafc;
		border: 1px solid #cbd5e1;
		border-radius: 12px;
		padding: 12px 16px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-wrap: wrap;
		gap: 10px;
	}
	.selection-summary-bar.is-ready {
		background: #ecfdf5;
		border-color: #6ee7b7;
	}
</style>

<div class="row justify-content-center">
	<div class="col-lg-10 col-xl-9">
		<div class="card box-turno">
			<div class="card-body p-4">
				<!-- Stepper Wizard -->
				<div class="wizard-steps mb-4">
					<div class="wizard-step-item active">
						<div class="wizard-step-circle"><i class="fas fa-compass"></i></div>
						<span class="wizard-step-title">1. Actividad</span>
					</div>
					<div class="wizard-step-item">
						<div class="wizard-step-circle">2</div>
						<span class="wizard-step-title">2. Día y Hora</span>
					</div>
					<div class="wizard-step-item">
						<div class="wizard-step-circle">3</div>
						<span class="wizard-step-title">3. Tus Datos</span>
					</div>
				</div>

				<div class="text-center mb-4">
					<h4 class="font-weight-bold text-dark mb-1">
						<i class="fas fa-sitemap text-primary mr-2"></i>Seleccioná el Espacio y la Actividad
					</h4>
					<p class="text-muted small mb-0">
						Elegí una actividad con turnos disponibles para pasar al calendario.
					</p>
				</div>

				<!-- Alerta inline amigable si falta elegir -->
				<div id="paso1-warning" class="alert alert-warning border-0 shadow-xs mb-4" style="display: none; border-radius: 12px; background: #fffbeb; color: #92400e; border-left: 4px solid #f59e0b !important;">
					<i class="fas fa-exclamation-circle mr-2"></i>
					<span id="paso1-warning-text">Por favor elegí una actividad disponible antes de continuar.</span>
				</div>

				{!! Form::open(['route' => 'tramite.paso2', 'class' => 'form-horizontal', 'method' => 'get', 'id' => 'form-paso1']) !!}
					<input type="hidden" name="dependencia_id" id="input_dependencia_id" value="{{ $dependencia_id ?: '' }}">
					<input type="hidden" name="dependencia_tramite_id" id="input_dependencia_tramite_id" value="{{ $dependencia_tramite_id ?: '' }}">

					<!-- SUB-PASO A: DEPENDENCIA / ESPACIO -->
					<div class="mb-4">
						<div class="paso1-section-header">
							<span class="paso1-step-num">A</span>
							<div>
								<h5 class="font-weight-bold text-dark mb-0">Espacio u Oficina</h5>
								<small id="auto-dep-hint" class="text-success font-weight-bold d-block" style="display: none !important;"></small>
							</div>
						</div>

						<div id="dependencias-grid" class="select-card-grid"></div>
					</div>

					<!-- SUB-PASO B: ACTIVIDAD / TRÁMITE -->
					<div class="mb-4" id="bloque-tramites">
						<div class="paso1-section-header">
							<span class="paso1-step-num" id="badge-step-b">B</span>
							<div>
								<h5 class="font-weight-bold text-dark mb-0 d-inline">Tipo de Visita o Actividad</h5>
								<button type="button" class="help-tip ml-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Cada tarjeta indica si la reserva es individual, grupal, para escuelas o mixta.">?</button>
							</div>
						</div>

						<div id="tramites-container">
							<div class="p-4 text-center bg-light rounded-lg border" style="border-radius: 12px;">
								<i class="fas fa-arrow-up text-primary mb-2 d-block fa-lg"></i>
								<span class="text-muted font-weight-bold">Primero elegí un espacio arriba para ver sus actividades disponibles.</span>
							</div>
						</div>
					</div>

					<!-- Barra de resumen de selección -->
					<div id="paso1-summary" class="selection-summary-bar mb-4">
						<div class="d-flex align-items-center" style="gap: 10px;">
							<i id="paso1-summary-icon" class="fas fa-clock text-muted fa-lg"></i>
							<div>
								<small class="text-uppercase font-weight-bold text-muted d-block" style="font-size: 0.72rem; letter-spacing: 0.5px;">Tu selección actual</small>
								<span id="paso1-summary-text" class="font-weight-bold text-dark">Todavía no elegiste una actividad.</span>
							</div>
						</div>
					</div>

					<hr class="my-4">

					<div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 12px;">
						<a href="{{ url('/') }}" class="btn btn-outline-secondary font-weight-bold" style="border-radius: 10px; padding: 10px 20px;">
							<i class="fas fa-arrow-left mr-1"></i> Volver al Observatorio
						</a>
						<button type="submit" id="btn-paso1-next" class="btn btn-gradient-primary btn-lg">
							Siguiente: Elegir Día y Hora <i class="fas fa-arrow-right ml-2"></i>
						</button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop

@section('script')
<script>
	(function() {
		var dependencias = @json(array_values($dependencias ?? []));
		var initialDepId = "{{ $dependencia_id ?: '' }}";
		var initialTramiteId = "{{ $dependencia_tramite_id ?: '' }}";
		var tramitesUrlBase = "{{ url('gettramites') }}";

		var selectedDep = null;
		var selectedTramite = null;
		var currentTramites = [];

		function escapeHtml(str) {
			return String(str || '')
				.replace(/&/g, '&amp;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;')
				.replace(/"/g, '&quot;')
				.replace(/'/g, '&#39;');
		}

		function getModalidadMeta(t) {
			var mod = (t.tipo_modalidad || 'individual').toLowerCase();
			var reqInst = Number(t.requiere_institucion) === 1 || t.requiere_institucion === true;
			var minP = t.min_personas_reserva || 2;
			var maxP = t.max_personas_reserva || 50;

			if (mod === 'mixto') {
				return {
					label: 'Mixto (Indiv. / Grupal / Escuela)',
					pillClass: 'pill-mod-mixto',
					icon: 'fas fa-layer-group',
					desc: 'Permite elegir modalidad en el paso de datos.'
				};
			}
			if (mod === 'institucional' || reqInst) {
				return {
					label: 'Escuelas / Instituciones',
					pillClass: 'pill-mod-institucional',
					icon: 'fas fa-school',
					desc: 'Delegaciones escolares o cátedras (de ' + minP + ' a ' + maxP + ' asistentes).'
				};
			}
			if (mod === 'grupal') {
				return {
					label: 'Visita Grupal',
					pillClass: 'pill-mod-grupal',
					icon: 'fas fa-users',
					desc: 'Grupos o familias (de ' + minP + ' a ' + maxP + ' personas).'
				};
			}
			return {
				label: 'Individual',
				pillClass: 'pill-mod-individual',
				icon: 'fas fa-user',
				desc: 'Reserva para 1 persona.'
			};
		}

		function showWarning(msg) {
			$('#paso1-warning-text').text(msg);
			$('#paso1-warning').slideDown(200);
			$('html, body').animate({ scrollTop: $('#paso1-warning').offset().top - 100 }, 250);
		}

		function hideWarning() {
			$('#paso1-warning').hide();
		}

		function updateSummary() {
			var $bar = $('#paso1-summary');
			var $icon = $('#paso1-summary-icon');
			var $text = $('#paso1-summary-text');

			if (selectedDep && selectedTramite) {
				$bar.addClass('is-ready');
				$icon.attr('class', 'fas fa-check-circle text-success fa-lg');
				$text.html(escapeHtml(selectedDep.nombre) + ' <i class="fas fa-angle-right mx-1 text-success"></i> ' + escapeHtml(selectedTramite.nombre));
			} else if (selectedDep) {
				$bar.removeClass('is-ready');
				$icon.attr('class', 'fas fa-hand-pointer text-primary fa-lg');
				$text.html('Espacio elegido: <strong>' + escapeHtml(selectedDep.nombre) + '</strong>. Ahora tocá una actividad disponible arriba.');
			} else {
				$bar.removeClass('is-ready');
				$icon.attr('class', 'fas fa-clock text-muted fa-lg');
				$text.text('Todavía no elegiste un espacio ni una actividad.');
			}
		}

		function renderDependencias() {
			var $grid = $('#dependencias-grid');
			$grid.empty();

			if (!dependencias.length) {
				$grid.html('<div class="p-4 text-center bg-light rounded-lg border w-100" style="grid-column: 1 / -1; border-radius: 12px;">'
					+ '<i class="fas fa-calendar-times fa-2x text-muted mb-2 d-block"></i>'
					+ '<p class="font-weight-bold text-dark mb-1">No hay oficinas habilitadas con turnos en este momento</p>'
					+ '<small class="text-muted">Por favor volvé a intentar más tarde.</small>'
					+ '</div>');
				return;
			}

			dependencias.forEach(function(dep) {
				var hasTurns = !!dep.has_turns;
				var isSelected = selectedDep && String(selectedDep.id) === String(dep.id);
				var cls = 'select-card' + (isSelected ? ' is-selected' : '') + (!hasTurns ? ' is-disabled' : '');

				var html = '<button type="button" class="' + cls + '" data-dep-id="' + dep.id + '"' + (!hasTurns ? ' disabled aria-disabled="true"' : '') + '>'
					+ '<div class="select-card-top">'
					+ '<span class="select-card-icon"><i class="fas fa-university"></i></span>'
					+ '<span class="select-card-check"><i class="fas fa-check-circle"></i></span>'
					+ '</div>'
					+ '<div class="select-card-title">' + escapeHtml(dep.nombre) + '</div>'
					+ '<div class="select-card-badges">'
					+ (hasTurns
						? '<span class="pill-badge pill-available"><i class="fas fa-check"></i> Con turnos disponibles</span>'
						: '<span class="pill-badge pill-unavailable"><i class="fas fa-ban"></i> Sin cupos por ahora</span>')
					+ '</div>'
					+ '</button>';

				$grid.append(html);
			});

			$grid.find('.select-card:not(.is-disabled)').on('click', function() {
				var depId = $(this).data('dep-id');
				selectDependencia(depId, false);
			});
		}

		function selectDependencia(depId, isAuto) {
			hideWarning();
			var dep = dependencias.find(function(d) { return String(d.id) === String(depId); });
			if (!dep || !dep.has_turns) return;

			selectedDep = dep;
			selectedTramite = null;
			$('#input_dependencia_id').val(dep.id);
			$('#input_dependencia_tramite_id').val('');

			if (isAuto) {
				$('#auto-dep-hint').text('Seleccionado automáticamente (único espacio con turnos disponibles).').css('display', 'block');
			} else {
				$('#auto-dep-hint').hide();
			}

			renderDependencias();
			updateSummary();
			loadTramites(dep.id);
		}

		function loadTramites(depId) {
			var $container = $('#tramites-container');
			$container.html('<div class="p-4 text-center bg-light rounded-lg border" style="border-radius: 12px;">'
				+ '<i class="fas fa-spinner fa-spin fa-2x text-primary mb-2 d-block"></i>'
				+ '<span class="font-weight-bold text-muted">Buscando actividades y visitas disponibles...</span>'
				+ '</div>');

			$.ajax({
				url: tramitesUrlBase + '/' + depId,
				method: 'GET',
				dataType: 'json',
				success: function(data) {
					currentTramites = Array.isArray(data) ? data : [];
					renderTramites();

					// Restaurar trámite previo en sesión o autoseleccionar si hay solo 1 disponible
					var disponibles = currentTramites.filter(function(t) { return !!t.has_turns; });
					if (initialTramiteId) {
						var match = disponibles.find(function(t) { return String(t.id) === String(initialTramiteId); });
						if (match) {
							selectTramite(match.id);
							initialTramiteId = '';
							return;
						}
					}
					if (disponibles.length === 1) {
						selectTramite(disponibles[0].id);
					}
				},
				error: function() {
					$container.html('<div class="alert alert-danger mb-0" style="border-radius: 12px;">'
						+ '<i class="fas fa-exclamation-triangle mr-2"></i>No pudimos cargar las actividades. Por favor recargá la página e intentá nuevamente.'
						+ '</div>');
				}
			});
		}

		function renderTramites() {
			var $container = $('#tramites-container');
			$container.empty();

			if (!currentTramites.length) {
				$container.html('<div class="p-4 text-center bg-light rounded-lg border" style="border-radius: 12px;">'
					+ '<i class="fas fa-info-circle fa-2x text-muted mb-2 d-block"></i>'
					+ '<p class="font-weight-bold text-dark mb-1">No hay actividades cargadas para este espacio</p>'
					+ '<small class="text-muted">Probá seleccionando otro espacio.</small>'
					+ '</div>');
				return;
			}

			var $grid = $('<div class="select-card-grid"></div>');
			var ordenados = currentTramites.slice().sort(function(a, b) {
				return (b.has_turns ? 1 : 0) - (a.has_turns ? 1 : 0);
			});
			ordenados.forEach(function(t) {
				var hasTurns = !!t.has_turns;
				var isSelected = selectedTramite && String(selectedTramite.id) === String(t.id);
				var meta = getModalidadMeta(t);
				var cls = 'select-card' + (isSelected ? ' is-selected' : '') + (!hasTurns ? ' is-disabled' : '');

				var html = '<button type="button" class="' + cls + '" data-tramite-id="' + t.id + '"' + (!hasTurns ? ' disabled aria-disabled="true"' : '') + '>'
					+ '<div>'
					+ '<div class="select-card-top">'
					+ '<span class="select-card-icon"><i class="' + meta.icon + '"></i></span>'
					+ '<span class="select-card-check"><i class="fas fa-check-circle"></i></span>'
					+ '</div>'
					+ '<div class="select-card-title">' + escapeHtml(t.nombre) + '</div>'
					+ '<div class="select-card-desc">' + (hasTurns ? escapeHtml(meta.desc) : 'Actualmente no hay fechas abiertas con cupos para esta actividad.') + '</div>'
					+ '</div>'
					+ '<div class="select-card-badges">'
					+ (hasTurns
						? '<span class="pill-badge pill-available"><i class="fas fa-check"></i> Disponible</span>'
						: '<span class="pill-badge pill-unavailable"><i class="fas fa-ban"></i> Sin cupos</span>')
					+ '<span class="pill-badge ' + meta.pillClass + '"><i class="' + meta.icon + '"></i> ' + escapeHtml(meta.label) + '</span>'
					+ '</div>'
					+ '</button>';

				$grid.append(html);
			});

			$container.append($grid);

			$grid.find('.select-card:not(.is-disabled)').on('click', function() {
				var tId = $(this).data('tramite-id');
				selectTramite(tId);
			});
		}

		function selectTramite(tramiteId) {
			hideWarning();
			var t = currentTramites.find(function(item) { return String(item.id) === String(tramiteId); });
			if (!t || !t.has_turns) return;

			selectedTramite = t;
			$('#input_dependencia_tramite_id').val(t.id);
			renderTramites();
			updateSummary();
		}

		$('#form-paso1').on('submit', function(e) {
			if (!$('#input_dependencia_id').val()) {
				e.preventDefault();
				showWarning('Primero tocá un espacio u oficina marcada como "Con turnos disponibles".');
				return false;
			}
			if (!$('#input_dependencia_tramite_id').val()) {
				e.preventDefault();
				showWarning('Por favor elegí una visita o actividad disponible antes de pasar al calendario.');
				return false;
			}
			$('#btn-paso1-next').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Cargando calendario...');
		});

		$(document).ready(function() {
			renderDependencias();

			var disponibles = dependencias.filter(function(d) { return !!d.has_turns; });
			if (initialDepId) {
				var prevDep = disponibles.find(function(d) { return String(d.id) === String(initialDepId); });
				if (prevDep) {
					selectDependencia(prevDep.id, false);
					return;
				}
			}
			if (disponibles.length === 1) {
				selectDependencia(disponibles[0].id, true);
			}
		});
	})();
</script>
@stop