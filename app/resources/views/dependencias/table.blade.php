<div class="tab-content">
    {{-- TAB 1: ESTRUCTURA JERÁRQUICA (ORGANIGRAMA / ÁRBOL) --}}
    <div class="tab-pane fade active in" id="tab_tree">
        <div class="alert alert-info" style="border-left: 4px solid #31708f; background-color: #d9edf7; color: #31708f; margin-top: 10px; margin-bottom: 20px;">
            <i class="fa fa-info-circle fa-lg"></i> 
            <strong>Estructura Organizacional:</strong> Haga clic en los botones de despliegue <i class="fa fa-chevron-down"></i> para expandir o contraer las dependencias hijas.
        </div>

        <div class="tree-wrapper" style="padding: 10px; background: #ffffff; border: 1px solid #e6e9ed; border-radius: 4px;">
            @php
                function renderCleanTree($nodes) {
                    if (empty($nodes) || count($nodes) === 0) return;
                    echo '<ul class="dep-tree-ul" style="list-style: none; padding-left: 20px; margin-bottom: 5px;">';
                    foreach ($nodes as $node) {
                        $isUA = $node->es_unidad_academica;
                        $hasChildren = count($node->children) > 0;
                        $nodeId = 'dep-node-' . $node->id;
                        
                        echo '<li style="margin: 6px 0; position: relative;">';
                        echo '<div class="tree-node-card" style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: ' . ($isUA ? '#f4f8fb' : '#fafafa') . '; border: 1px solid ' . ($isUA ? '#bce8f1' : '#e0e0e0') . '; border-left: 4px solid ' . ($isUA ? '#337ab7' : '#5bc0de') . '; border-radius: 3px;">';
                        
                        // Icon & Title
                        echo '<div style="display: flex; align-items: center; gap: 10px;">';
                        if ($hasChildren) {
                            echo '<button class="btn btn-default btn-xs tree-toggle" data-target="#' . $nodeId . '" style="padding: 1px 6px; margin-right: 5px;"><i class="fa fa-chevron-down text-primary"></i></button>';
                        } else {
                            echo '<span style="display: inline-block; width: 22px;"></span>';
                        }
                        
                        if ($isUA) {
                            echo '<i class="fa fa-university text-primary" style="font-size: 16px;"></i>';
                        } else {
                            echo '<i class="fa ' . ($hasChildren ? 'fa-folder-open text-warning' : 'fa-building-o text-muted') . '" style="font-size: 15px;"></i>';
                        }
                        
                        echo '<strong style="font-size: 13px; color: #333;">' . e($node->nombre) . '</strong>';
                        
                        if ($node->codigo) {
                            echo '<span class="label label-default" style="font-size: 10px; margin-left: 5px;">' . e($node->codigo) . '</span>';
                        }
                        echo '</div>';

                        // Badges & Actions
                        echo '<div style="display: flex; align-items: center; gap: 8px;">';
                        echo '<span class="label ' . ($isUA ? 'label-primary' : 'label-info') . '" style="font-size: 10px;">Nivel ' . e($node->nivel) . '</span>';
                        if ($isUA) {
                            echo '<span class="label label-success" style="font-size: 10px;"><i class="fa fa-star"></i> Unidad Académica</span>';
                        }
                        if ($node->tipoDependencia) {
                            echo '<span class="label label-default" style="font-size: 10px;">' . e($node->tipoDependencia->name) . '</span>';
                        }
                        
                        echo '<div class="btn-group btn-group-xs" style="margin-left: 10px;">';
                        echo '<a href="' . route('dependencias.edit', [$node->id]) . '" class="btn btn-default btn-xs" title="Editar"><i class="fa fa-edit"></i></a>';
                        echo '<form action="' . route('dependencias.destroy', [$node->id]) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'¿Está seguro de eliminar esta dependencia?\')">';
                        echo csrf_field();
                        echo method_field('DELETE');
                        echo '<button type="submit" class="btn btn-danger btn-xs" title="Eliminar"><i class="fa fa-trash"></i></button>';
                        echo '</form>';
                        echo '</div>';
                        echo '</div>';

                        echo '</div>'; // end node card

                        // Children
                        if ($hasChildren) {
                            echo '<div id="' . $nodeId . '" class="tree-children-container" style="display: block;">';
                            renderCleanTree($node->children);
                            echo '</div>';
                        }
                        echo '</li>';
                    }
                    echo '</ul>';
                }

                if (isset($dependenciasTree) && count($dependenciasTree) > 0) {
                    renderCleanTree($dependenciasTree);
                } else {
                    echo '<div class="alert alert-warning text-center">No hay dependencias registradas.</div>';
                }
            @endphp
        </div>
    </div>

    {{-- TAB 2: TABLA DETALLADA CON DATATABLES NATIVO --}}
    <div class="tab-pane fade" id="tab_table">
        <table id="datatable" class="table table-striped table-bordered" style="width: 100%;">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Nombre Dependencia</th>
                    <th>Código</th>
                    <th style="width: 60px;">Nivel</th>
                    <th>Dependencia Padre</th>
                    <th>Tipo / Agrupación</th>
                    <th>Categoría</th>
                    <th style="width: 80px;" class="text-center">Acción</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($dependenciasList))
                    @foreach($dependenciasList as $dep)
                        <tr>
                            <td><strong>{{ $dep->id }}</strong></td>
                            <td>
                                @if($dep->es_unidad_academica)
                                    <i class="fa fa-university text-primary mr-1"></i>
                                @else
                                    <i class="fa fa-building-o text-muted mr-1"></i>
                                @endif
                                <strong>{{ $dep->nombre }}</strong>
                            </td>
                            <td>
                                @if($dep->codigo)
                                    <span class="label label-default">{{ $dep->codigo }}</span>
                                @else
                                    <span class="text-muted">&mdash;</span>
                                @endif
                            </td>
                            <td>
                                <span class="label label-info">Nivel {{ $dep->nivel }}</span>
                            </td>
                            <td>
                                @if($dep->parent)
                                    <span class="text-primary"><i class="fa fa-level-up fa-rotate-90 text-muted"></i> {{ $dep->parent->nombre }}</span>
                                @else
                                    <span class="label label-default">Raíz (Principal)</span>
                                @endif
                            </td>
                            <td>
                                @if($dep->tipoDependencia)
                                    {{ $dep->tipoDependencia->name }}
                                @else
                                    <span class="text-muted">&mdash;</span>
                                @endif
                            </td>
                            <td>
                                @if($dep->es_unidad_academica)
                                    <span class="label label-success"><i class="fa fa-check"></i> Unidad Académica</span>
                                @else
                                    <span class="label label-default">Área / Depto</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {!! Form::open(['route' => ['dependencias.destroy', $dep->id], 'method' => 'delete', 'style' => 'margin: 0px; display:inline;']) !!}
                                    <div class="btn-group btn-group-xs">
                                        <a href="{{ route('dependencias.edit', [$dep->id]) }}" class="btn btn-default btn-xs" title="Editar"><i class="fa fa-edit"></i></a>
                                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('¿Esta seguro?')"]) !!}
                                    </div>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Tree Expand/Collapse
        $(document).on('click', '.tree-toggle', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            var icon = $(this).find('i');
            $(target).slideToggle(200, function() {
                if ($(this).is(':visible')) {
                    icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
                } else {
                    icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
                }
            });
        });
    });
</script>