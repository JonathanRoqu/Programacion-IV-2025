<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;

class NoticiaController extends Controller
{
    public function create()
    {
        return view('noticias.create'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required',
            'imagen' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $noticia = new Noticia();
        $noticia->titulo = $request->titulo;
        $noticia->descripcion = $request->descripcion;
        $noticia->categoria = $request->categoria;
        $noticia->autor = auth()->user()->name ?? 'Anónimo'; // Si tienes autenticación
        $noticia->fecha = now();

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('noticias', 'public');
            $noticia->imagen = $path;
        }
        $noticia->save();

        return redirect()->route('noticias.show', $noticia->id)
            ->with('success', 'Noticia publicada exitosamente!');
    }

    // Mostrar noticia individual
    public function show($id)
    {
        $noticia = Noticia::findOrFail($id);
        return view('noticias.show', compact('noticia'));
    }

    // Listar todas las noticias
    public function index()
    {
        $noticias = Noticia::latest()->paginate(10);
        return view('noticias.index', compact('noticias'));
    }
}