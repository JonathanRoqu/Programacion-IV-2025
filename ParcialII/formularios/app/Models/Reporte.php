<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $fillable = [
        'noticia_id',
        'motivo',
        'comentario',
        'nombre_usuario',
        'fecha_reporte'
    ];

    protected $casts = [
        'fecha_reporte' => 'datetime'
    ];

    public function noticia()
    {
        return $this->belongsTo(Noticia::class);
    }
}
