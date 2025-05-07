@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <article class="news-detail">
        <h1 class="mb-4">{{ $noticia->titulo }}</h1>

        <div class="meta mb-4">
            <span class="badge bg-primary">{{ $noticia->categoria }}</span>
            <span class="text-muted ms-2">
                Publicado por {{ $noticia->autor }} el {{ $noticia->fecha->format('d/m/Y') }}
            </span>
        </div>

        @if($noticia->imagen)
        <img src="{{ $noticia->imagenUrl }}" 
             alt="{{ $noticia->titulo }}" 
             class="img-fluid mb-4">
        @endif

        <div class="news-content">
            {!! nl2br(e($noticia->descripcion)) !!}
        </div>

        <!-- Botón de Reporte -->
        <div class="mt-4 pt-3 border-top">
            <a href="{{ route('reportes.create', ['noticia_id' => $noticia->id]) }}" 
               class="btn btn-danger">
               <i class="fas fa-flag"></i> Reportar Noticia
            </a>
        </div>
    </article>
</div>
@endsection