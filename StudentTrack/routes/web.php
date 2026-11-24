<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\KalenderLiburController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::resource('mata-kuliah', MataKuliahController::class);

Route::resource('tugas', TugasController::class)
    ->parameters(['tugas' => 'tugas']);

Route::resource('nilai', NilaiController::class);

Route::get('/kalender-libur', [KalenderLiburController::class, 'index'])
    ->name('kalender-libur.index');

Route::get('/nilai/export', [NilaiController::class, 'export'])
    ->name('nilai.export');

Route::post('/nilai/import', [NilaiController::class, 'import'])
    ->name('nilai.import');
