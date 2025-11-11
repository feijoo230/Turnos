<table id="datatable" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Nro</th>
      <th>Fecha</th>
      <th>Motivo</th>
      <th>Dependencia Origen</th>
      <th>Dependencia Destino</th>
      <th>Usuario Origen</th>
      <th>Usuario Destino</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
  @foreach($pases as $pase)
    <tr>
      <td>{!! $pase->motivo !!}</td>
      <td>{!! $pase->motivo !!}</td>
      <td>{!! $pase->motivo !!}</td>
      <td>{!! $pase->motivo !!}</td>
      <td>{!! $pase->motivo !!}</td>
      <td>{!! $pase->motivo !!}</td>
      <td>{!! $pase->motivo !!}</td>
      <td>
        {!! Form::open(['route' => ['pases.destroy', $pase->id], 'method' => 'delete']) !!}
          <div class='btn-group'>
              <a href="{!! route('pases.show', [$pase->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
              <a href="{!! route('pases.edit', [$pase->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
              {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')"]) !!}
          </div>
        {!! Form::close() !!}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>