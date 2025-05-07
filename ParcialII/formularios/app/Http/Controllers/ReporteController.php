<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Reporte;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReporteController extends Controller
{
    // Mostrar formulario de reporte
    public function create(Request $request)
    {
        $request->validate([
            'noticia_id' => 'required|exists:noticias,id'
        ]);

        $noticia = Noticia::findOrFail($request->noticia_id);

        return view('reportes.create', [
            'noticia' => $noticia,
            'motivos' => [
                'Contenido falso',
                'Lenguaje ofensivo',
                'Información desactualizada',
                'Violación de derechos',
                'Otro'
            ]
        ]);
    }

    // Procesar reporte
    public function store(Request $request)
    {
        $validated = $request->validate([
            'noticia_id' => 'required|exists:noticias,id',
            'motivo' => 'required|string|max:100',
            'comentario' => 'required|string|min:20|max:500',
            'nombre_usuario' => 'required|string|max:100'
        ]);

        $reporte = Reporte::create([
            'noticia_id' => $validated['noticia_id'],
            'motivo' => $validated['motivo'],
            'comentario' => $validated['comentario'],
            'nombre_usuario' => $validated['nombre_usuario'],
            'fecha_reporte' => Carbon::now()
        ]);

        return redirect()->route('reportes.show', $reporte->id)
               ->with('success', '¡Reporte enviado correctamente!');
    }

    // Mostrar reporte individual
    public function show($id)
    {
        $reporte = Reporte::with('noticia')->findOrFail($id);

        return view('reportes.show', [
            'reporte' => $reporte,
            'noticia' => $reporte->noticia
        ]);
    }
}