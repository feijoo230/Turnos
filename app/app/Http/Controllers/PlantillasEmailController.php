<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlantillaEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PlantillasEmailController extends Controller
{
    /**
     * Create a new controller instance.
     * Solo administradores tienen acceso a la gestión de plantillas de correo.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMINISTRADOR']);
    }

    /**
     * Muestra el panel principal de plantillas de email con filtros por circuito.
     */
    public function index(Request $request)
    {
        $circuito = $request->input('circuito', 'todos');
        $evento = $request->input('evento', 'todos');
        $search = $request->input('search');

        $query = PlantillaEmail::query();

        if ($circuito !== 'todos' && !empty($circuito)) {
            $query->where('circuito', $circuito);
        }

        if ($evento !== 'todos' && !empty($evento)) {
            $query->where('evento', $evento);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('asunto', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%")
                  ->orWhere('clave', 'like', "%{$search}%");
            });
        }

        $plantillas = $query->orderBy('circuito', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Contadores y métricas generales
        $totalPlantillas = PlantillaEmail::count();
        $totalIndividual = PlantillaEmail::where('circuito', 'individual')->count();
        $totalColegio = PlantillaEmail::where('circuito', 'colegio')->count();
        $totalActivas = PlantillaEmail::where('activo', true)->count();

        return view('plantillas_email.index', compact(
            'plantillas',
            'circuito',
            'evento',
            'search',
            'totalPlantillas',
            'totalIndividual',
            'totalColegio',
            'totalActivas'
        ));
    }

    /**
     * Muestra la pantalla de edición para una plantilla específica.
     */
    public function edit($id)
    {
        $plantilla = PlantillaEmail::findOrFail($id);
        $variables = PlantillaEmail::getAvailableVariables($plantilla->circuito);
        $dummyData = PlantillaEmail::getDummyData($plantilla->circuito);
        $dummyRender = $plantilla->render($dummyData);

        return view('plantillas_email.edit', compact(
            'plantilla',
            'variables',
            'dummyData',
            'dummyRender'
        ));
    }

    /**
     * Actualiza la plantilla en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $plantilla = PlantillaEmail::findOrFail($id);

        $request->validate([
            'asunto' => 'required|string|max:255',
            'cuerpo_html' => 'required|string',
            'descripcion' => 'nullable|string|max:1000',
            'activo' => 'nullable|boolean'
        ], [
            'asunto.required' => 'El asunto del correo es obligatorio.',
            'cuerpo_html.required' => 'El contenido o cuerpo HTML del correo no puede estar vacío.'
        ]);

        $plantilla->asunto = $request->input('asunto');
        $plantilla->cuerpo_html = $request->input('cuerpo_html');
        $plantilla->descripcion = $request->input('descripcion');
        $plantilla->activo = $request->has('activo') ? (bool) $request->input('activo') : false;

        $plantilla->save();

        return redirect()->route('plantillas-email.index')
            ->with('success', "La plantilla '{$plantilla->nombre}' ha sido actualizada exitosamente.");
    }

    /**
     * Retorna la vista previa HTML renderizada con datos simulados del circuito.
     */
    public function preview($id)
    {
        $plantilla = PlantillaEmail::findOrFail($id);
        $dummyData = PlantillaEmail::getDummyData($plantilla->circuito);
        $rendered = $plantilla->render($dummyData);

        return response($rendered['cuerpo_html'])
            ->header('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * Envía un correo electrónico real de prueba con datos simulados del circuito correspondiente.
     */
    public function enviarPrueba(Request $request, $id)
    {
        $request->validate([
            'email_prueba' => 'required|email'
        ], [
            'email_prueba.required' => 'Debe ingresar una dirección de correo para la prueba.',
            'email_prueba.email' => 'La dirección de correo ingresada no es válida.'
        ]);

        $plantilla = PlantillaEmail::findOrFail($id);
        $destinatario = $request->input('email_prueba');

        $dummyData = PlantillaEmail::getDummyData($plantilla->circuito);
        $rendered = $plantilla->render($dummyData);

        $fromEmail = config('mail.from.address', 'turnos@unsa.edu.ar');
        $fromName = config('mail.from.name', 'Sistema de Turnos UNSa');

        try {
            Mail::send([], [], function ($message) use ($destinatario, $rendered, $fromEmail, $fromName) {
                $message->from($fromEmail, $fromName)
                    ->to($destinatario)
                    ->subject('[PRUEBA] ' . $rendered['asunto'])
                    ->html($rendered['cuerpo_html']);
            });

            return back()->with('success', "Correo de prueba enviado exitosamente a {$destinatario} utilizando la plantilla '{$plantilla->nombre}'.");
        } catch (\Exception $e) {
            Log::error("Error al enviar correo de prueba para plantilla {$plantilla->clave}: " . $e->getMessage());
            return back()->with('error', "No se pudo despachar el correo de prueba: " . $e->getMessage());
        }
    }

    /**
     * Restablece la plantilla a sus valores de fábrica originales.
     */
    public function restablecer($id)
    {
        $plantilla = PlantillaEmail::findOrFail($id);
        $defaults = PlantillaEmail::getDefaultTemplates();

        if (!isset($defaults[$plantilla->clave])) {
            return back()->with('error', "No se encontraron valores predeterminados para la plantilla '{$plantilla->clave}'.");
        }

        $defaultData = $defaults[$plantilla->clave];
        $plantilla->asunto = $defaultData['asunto'];
        $plantilla->cuerpo_html = $defaultData['cuerpo_html'];
        $plantilla->descripcion = $defaultData['descripcion'];
        $plantilla->activo = true;
        $plantilla->save();

        return back()->with('success', "La plantilla '{$plantilla->nombre}' ha sido restablecida a sus valores predeterminados de fábrica.");
    }

    /**
     * Alterna el estado activo / inactivo de la plantilla.
     */
    public function toggle($id)
    {
        $plantilla = PlantillaEmail::findOrFail($id);
        $plantilla->activo = !$plantilla->activo;
        $plantilla->save();

        $estadoTexto = $plantilla->activo ? 'activada' : 'desactivada';
        return back()->with('success', "La plantilla '{$plantilla->nombre}' ha sido {$estadoTexto}.");
    }
}
