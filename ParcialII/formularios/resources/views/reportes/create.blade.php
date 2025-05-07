<!-- resources/views/reportes/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Reportar Noticia: {{ $noticia->titulo }}</h1>
    
    <form method="POST" action="{{ route('reportes.store') }}">
        @csrf
        <input type="hidden" name="noticia_id" value="{{ $noticia->id }}">

        <div class="mb-3">
            <label class="form-label">Tu nombre</label>
            <input type="text" class="form-control" name="nombre_usuario" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Motivo del reporte</label>
            <select class="form-select" name="motivo" required>
                <option value="">Seleccione un motivo</option>
                <option value="Contenido falso">Contenido falso</option>
                <option value="Lenguaje ofensivo">Lenguaje ofensivo</option>
                <option value="Información desactualizada">Información desactualizada</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Explicación detallada</label>
            <textarea class="form-control" name="comentario" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-danger">Enviar Reporte</button>
    </form>
</div>
@endsection

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class Noticia extends Model
{
    /**
     * Atributos asignables masivamente
     */
    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria',
        'imagen',
        'autor',
        'fecha'
    ];

    /**
     * Conversiones de tipo
     */
    protected $casts = [
        'fecha' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relación con reportes (1:N)
     */
    public function reportes()
    {
        return $this->hasMany(Reporte::class);
    }

    /**
     * Accesor para la URL de la imagen
     */
    protected function imagenUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->getImagenUrl()
        );
    }

    /**
     * Accesor para fecha formateada
     */
    protected function fechaFormateada(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fecha?->format('d/m/Y') ?? 'Fecha no disponible'
        );
    }

    /**
     * Obtiene la URL completa de la imagen
     */
    public function getImagenUrl()
    {
        if (empty($this->imagen)) {
            return asset('images/default-news.jpg');
        }

        return str_starts_with($this->imagen, 'http') 
            ? $this->imagen 
            : asset('storage/' . $this->imagen);
    }

    /**
     * Scope para noticias recientes
     */
    public function scopeRecientes($query)
    {
        return $query->where('fecha', '>=', now()->subDays(30));
    }

    /**
     * Scope para búsqueda por categoría
     */
    public function scopeDeCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }
}