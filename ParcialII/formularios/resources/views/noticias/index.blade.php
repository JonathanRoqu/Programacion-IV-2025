@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Todas las Noticias</h1>
    
    <div class="row">
        @foreach($noticias as $noticia)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($noticia->imagen)
                <img src="{{ $noticia->imagenUrl }}" class="card-img-top" alt="{{ $noticia->titulo }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $noticia->titulo }}</h5>
                    <p class="card-text">{{ Str::limit($noticia->descripcion, 100) }}</p>
                    <a href="{{ route('noticias.show', $noticia->id) }}" class="btn btn-primary">Leer más</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection