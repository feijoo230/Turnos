<table id="datatable" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th width="80px">Nro.</th>
      <th width="80px">Fecha</th>
      <th width="100px">Causante</th>
      <th width="200px">Asunto</th>
      <th width="100px">Email</th>
      <th width="120px">Dependencia</th>
      <th width="200px">Documentos</th>
      <th width="130px">Acción</th>
    </tr>
  </thead>
  <tbody>
  @foreach($tramites as $tramite)
    <tr>
      <td>{!! $tramite->nro_tramite !!}</td>
      <td>{!! $tramite->created_at->format('d/m/Y H:i') !!}</td>
      <td>{!! $tramite->remitente !!}</td>
      <td>{!! $tramite->asunto !!}</td>
      <td>{!! $tramite->correo !!}</td>
      <td>{!! $tramite->dependencia->nombre !!}</td>
      <td>
        @foreach($tramite->documentos as $documento)
          <a href="{!! route('tramites.descargar', [$documento->id]) !!}" style="">{!! $documento->vcnombre !!}</a>
        @endforeach
      </td>
      <td>
        {!! Form::open(['route' => ['tramites.destroy', $tramite->id], 'method' => 'delete']) !!}
          <div class='btn-group text-center'>
              <a href="{!! route('tramites.show', [$tramite->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
              <a href="{!! route('tramites.edit', [$tramite->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
              {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Esta seguro?')"]) !!}
              <a href="{!! route('pases.listar', [$tramite->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-share"></i></a>
          </div>
        {!! Form::close() !!}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>