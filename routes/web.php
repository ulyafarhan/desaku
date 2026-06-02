<?php

use App\Http\Controllers\Web\CitizenAuthController;
use App\Http\Controllers\Web\CitizenDashboardController;
use App\Http\Controllers\Web\CitizenSubmissionController;
use App\Http\Controllers\Web\PublicPortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPortalController::class, 'home'])->name('home');
Route::get('/profil', [PublicPortalController::class, 'profile'])->name('profile');
Route::get('/informasi', [PublicPortalController::class, 'information'])->name('information.index');
Route::get('/informasi/{slug}', [PublicPortalController::class, 'informationShow'])->name('information.show');
Route::get('/verifikasi/{hash}', [PublicPortalController::class, 'verify'])->name('verify');

Route::middleware('guest:penduduk')->group(function () {
    Route::get('/login', [CitizenAuthController::class, 'create'])->name('login');
    Route::post('/login', [CitizenAuthController::class, 'store'])->name('login.store');
});

Route::post('/logout', [CitizenAuthController::class, 'destroy'])
    ->middleware('auth:penduduk')
    ->name('logout');

Route::prefix('warga')
    ->name('warga.')
    ->middleware('auth:penduduk')
    ->group(function () {
        Route::get('/dashboard', CitizenDashboardController::class)->name('dashboard');
        Route::get('/surat/ajukan/{kategori}', [CitizenSubmissionController::class, 'create'])->name('surat.create');
        Route::post('/surat/pengajuan', [CitizenSubmissionController::class, 'store'])->name('surat.store');
        Route::get('/pengajuan/{pengajuan}', [CitizenSubmissionController::class, 'show'])->name('pengajuan.show');
    });
