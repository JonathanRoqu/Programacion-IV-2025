@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Publicar Nueva Noticia</h1>

    <form method="POST" action="{{ route('noticias.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Campo: Título -->
        <div class="mb-3">
            <label for="titulo" class="form-label">Título de la noticia</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>

        <!-- Campo: Descripción -->
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required></textarea>
        </div>

        <!-- Campo: Categoría -->
        <div class="mb-3">
            <label for="categoria" class="form-label">Categoría</label>
            <select class="form-select" id="categoria" name="categoria" required>
                <option value="">Seleccione una categoría</option>
                <option value="Deportes">Deportes</option>
                <option value="Clima">Clima</option>
                <option value="Educación">Educación</option>
            </select>
        </div>

        <!-- Campo: Imagen -->
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen (opcional)</label>
            <input type="file" class="form-control" id="imagen" name="imagen">
        </div>

        <button type="submit" class="btn btn-primary">Publicar Noticia</button>
    </form>
</div>
@endsection