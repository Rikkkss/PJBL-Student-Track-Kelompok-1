<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\KalenderLiburController;

/*
|--------------------------------------------------------------------------
| HALAMAN LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/', [LoginController::class, 'showLogin'])
        ->name('login');

    Route::get('/login', [LoginController::class, 'showLogin'])
        ->name('login.page');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.process');

});

/*
|--------------------------------------------------------------------------
| HALAMAN SETELAH LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,mahasiswa'])->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Mata Kuliah
    Route::resource('mata-kuliah', MataKuliahController::class);

    // Tugas
    Route::resource('tugas', TugasController::class)
        ->parameters(['tugas' => 'tugas']);

    // Export Excel
    Route::get('/nilai/export', [NilaiController::class, 'export'])
        ->name('nilai.export');

    // Import Excel
    Route::post('/nilai/import', [NilaiController::class, 'import'])
        ->name('nilai.import');

    // KHS PDF
    Route::get('/nilai/khs/{semester}/pdf', [
        NilaiController::class,
        'downloadKhs'
    ])->whereNumber('semester')->name('nilai.khs.pdf');

    // Transkrip PDF
    Route::get('/nilai/transkrip/pdf', [
        NilaiController::class,
        'downloadTranskrip'
    ])->name('nilai.transkrip.pdf');

    // Nilai Akademik
    Route::resource('nilai', NilaiController::class);

    // Kalender Libur
    Route::get('/kalender-libur', [
        KalenderLiburController::class,
        'index'
    ])->name('kalender-libur.index');

});

/*
|--------------------------------------------------------------------------
| KHUSUS ADMIN
|--------------------------------------------------------------------------
*/

// Route manajemen akun mahasiswa akan ditambahkan di tahap berikutnya.
