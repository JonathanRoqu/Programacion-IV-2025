<!-- resources/views/reportes/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h2>Reporte #{{ $reporte->id }}</h2>
        </div>
        <div class="card-body">
            <h4>Noticia reportada:</h4>
            <p>{{ $reporte->noticia->titulo }}</p>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <h5>Motivo:</h5>
                    <p class="text-danger">{{ $reporte->motivo }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Reportado por:</h5>
                    <p>{{ $reporte->nombre_usuario }}</p>
                </div>
            </div>
            
            <div class="mt-3">
                <h5>Comentario:</h5>
                <p class="p-3 bg-light rounded">{{ $reporte->comentario }}</p>
            </div>
            
            <a href="{{ route('noticias.show', $reporte->noticia_id) }}" 
               class="btn btn-outline-primary mt-3">
               Ver noticia original
            </a>
        </div>
        <div class="card-footer text-muted">
            Reportado el {{ $reporte->fecha_reporte->format('d/m/Y H:i') }}
        </div>
    </div>
</div>
@endsection