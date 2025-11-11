<small style="background: #D9EDF7">* Las siguientes son dependencias que agrupan las areas/departamentos/direcciones/etc.</small>
<table class="table table-sm">
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
    @foreach($dependencias as $dependencia)
      @if ($dependencia['es_unidad_academica'] == true)
        <tr class="info">
      @else
        <tr>
      @endif
        <td style="padding: 0px;">{!! $dependencia['nombre'] !!}</td>
        <td style="padding: 0px;">
          {!! Form::open(['route' => ['dependencias.destroy', $dependencia['id']], 'method' => 'delete', 'style' => 'margin: 0px;']) !!}
            <div class='btn-group'>
                <a href="{!! route('dependencias.edit', [$dependencia['id']]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit fa-2x"></i></a>
                {!! Form::button('<i class="fa fa-trash fa-2x"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')"]) !!}
            </div>
          {!! Form::close() !!}
        </td>
      </tr>
      @endforeach
  </tbody>
</table>