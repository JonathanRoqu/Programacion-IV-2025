<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\ReporteController;

Route::controller(NoticiaController::class)->group(function () {
    Route::get('/', 'index')->name('noticias.index');
    Route::get('/noticias/crear', 'create')->name('noticias.create');
    Route::post('/noticias', 'store')->name('noticias.store');
    Route::get('/noticias/{id}', 'show')->name('noticias.show');
});

Route::controller(ReporteController::class)->group(function () {
    Route::get('/reportes/crear', 'create')->name('reportes.create');
    Route::post('/reportes', 'store')->name('reportes.store');
    Route::get('/reportes/{id}', 'show')->name('reportes.show');
});

    