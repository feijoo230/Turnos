<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dependencia;
use App\Models\Usuariodependencia;
use App\Models\Tramite;
use App\Models\Documento;
use App\Http\Requests\StoreTramiteDigital;
use Illuminate\Support\Facades\Mail;
use App\Mail\NuevoTramite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TramitesDigitalesController extends Controller
{
    public function __construct()
    {

    }

    protected function index()
    {
        $usuario_id = Auth::id();
        $dependencias = Usuariodependencia::select('dependencia_id')->where('usuario_id', $usuario_id)->get()->toArray();

        $aux_dep = null;
        foreach ($dependencias as $value) {
           $aux_dep[] = $value['dependencia_id'];
        }
        
        $tramites = Tramite::whereIn('dependencia_id', $aux_dep)->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('tramites.index')->with(compact('tramites'));
    }

    public function buscar(Request $request)
    {
        $input = $request->all();
        $text_buscar = $input['text_buscar'];
        
        $tramites = Tramite::orderBy('created_at', 'desc')
            ->where('nro_tramite', 'like', "%{$text_buscar}%")
            ->orWhere('asunto', 'like', "%{$text_buscar}%")
            ->orWhere('remitente', 'like', '%'.$text_buscar.'%')
            ->orWhere('correo', 'like', "%{$text_buscar}%")
            ->paginate(15);
        
        return view('tramites.index')->with(compact('tramites'));
    }

    public function show($id)
    {
        $tramite = Tramite::find($id);

        if (empty($tramite)) {
            return 'ERROR!';
        }

        return view('tramites.show')->with(compact('tramite'));
    }

    public function edit($id)
    {
        $tramite = Tramite::find($id);
        $dependencias = Dependencia::where('id', 18)->orwhere('id', 21)->orwhere('id', 19)->pluck('nombre', 'id')->toArray();

        return view('tramites.edit')->with(compact('tramite', 'dependencias'));
    }

    public function update($id, StoreTramiteDigital $request)
    {
        $tramite = Tramite::find($id);
        $tramite->asunto = $request['asunto'];
        $tramite->remitente = $request['remitente'];
        $tramite->domicilio = $request['domicilio'];
        $tramite->telefono = $request['telefono'];
        $tramite->correo = $request['correo'];
        $tramite->dependencia_id = $request['dependencia_id'];

        try {
            $tramite->save();

            $files = $request->file('files');
            $aux_files = null;

            if($request->hasFile('files'))
            {
                foreach ($files as $file) {
                    $aux = null;
                    $aux['path'] = $file->getRealPath();
                    $aux['as'] = $file->getClientOriginalName();
                    $aux['mime'] = $file->getMimeType();
                    $aux['ext'] = $file->getClientOriginalExtension();
                    $aux_files[] = $aux;
                }
            }


            //guiardamos los documentos digitales
            $aFiles = null;
            $i = 0;
            foreach ($aux_files as $file) {
                $aux = null;
                $aux['vcnombre'] = $file['as'];
                $aux['vcnombrefis'] = Carbon::now();
                $aux['vcnombrefis'] = $tramite->id.'_'.$aux['vcnombrefis']->format('d_m_Y_his').'_'.$i;
                $aux['vcext'] = $file['ext'];
                $aux['mime'] = public_path().'/files';
                $aux['activo'] = 1;
                $aux['tramite_id'] = $tramite->id;
                $aFiles[] = $aux;
                ++$i;
            }
            $tramite->documentos()->createMany($aFiles);
        
        } catch (\Exception $e) {
            return redirect(route('tramite.index'))->with('error','Hubo un error en el envio del mensaje. Por favor intente nuevamente.');
        }

        return redirect(route('tramites.index'));
    }

    public function create()
    {
        $dependencias = Dependencia::where('id', 18)->orwhere('id', 21)->orwhere('id', 19)->pluck('nombre', 'id')->toArray();

        return view('tramites.create')->with(compact('dependencias'));
    }

    public function store(StoreTramiteDigital $request)
    {
        $tramite = new Tramite(
            [
                'name' => $request['name'],
                'asunto' => $request['asunto'],
                'remitente' => $request['remitente'],
                'domicilio' => $request['domicilio'],
                'telefono' => $request['telefono'],
                'correo'=> $request['correo'],
                'dependencia_id' => $request['dependencia_id']
            ]
        );

        $files = $request->file('files');
        $aux_files = null;

        if($request->hasFile('files'))
        {
            foreach ($files as $file) {
                $aux = null;
                $aux['path'] = $file->getRealPath();
                $aux['as'] = $file->getClientOriginalName();
                $aux['mime'] = $file->getMimeType();
                $aux['ext'] = $file->getClientOriginalExtension();
                $aux_files[] = $aux;
            }
        }
        try {
            $tramite->save();

            //guiardamos los documentos digitales
            $aFiles = null;
            $i = 0;
            foreach ($aux_files as $file) {
                $aux = null;
                $aux['vcnombre'] = $file['as'];
                $aux['vcnombrefis'] = Carbon::now();
                $aux['vcnombrefis'] = $tramite->id.'_'.$aux['vcnombrefis']->format('d_m_Y_his').'_'.$i;
                $aux['vcext'] = $file['ext'];
                $aux['mime'] = $file['mime'];
                $aux['path'] = public_path().'/files';
                $aux['activo'] = 1;
                $aux['tramite_id'] = $tramite->id;
                $aFiles[] = $aux;
                ++$i;

                //Se almacena los archivos en el disco
                Storage::disk('files')->put($aux['vcnombrefis'].'.'.$aux['vcext'], file_get_contents($file['path']));
            }


            $tramite->documentos()->createMany($aFiles);

        } catch (\Exception $e) {
            return redirect(route('tramite.index'))->with('error','Hubo un error en el envio del mensaje. Por favor intente nuevamente.');
        }

        return redirect(route('tramites.index'));
    }

    public function descargar($id)
    {
        $documento = Documento::find($id);


        return response()->download($documento->path.'/'.$documento->vcnombrefis.'.'.$documento->vcext, $documento->vcnombre);
    }
}
