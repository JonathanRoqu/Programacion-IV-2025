<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;   


class Noticia extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria',
        'imagen',
        'autor',
        'fecha'
    ];

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