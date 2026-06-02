<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InformasiPublikController;
use App\Http\Controllers\Api\MutasiPendudukController;
use App\Http\Controllers\Api\PengajuanSuratController;
use App\Http\Controllers\Api\StatistikController;
use App\Http\Controllers\Api\TelegramWebhookController;
use App\Http\Controllers\Api\VerifikasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::prefix('v1')->group(function () {

    // Auth
    Route::post('/auth/login/warga', [AuthController::class, 'loginWarga'])->middleware('throttle:5,1');
    Route::post('/auth/login/admin', [AuthController::class, 'loginAdmin'])->middleware('throttle:5,1');

    // Informasi Publik
    Route::get('/informasi', [InformasiPublikController::class, 'index']);
    Route::get('/informasi/{slug}', [InformasiPublikController::class, 'show']);

    // Statistik Publik
    Route::get('/statistik/demografi', [StatistikController::class, 'demografi']);
    Route::get('/statistik/layanan', [StatistikController::class, 'layanan']);

    // Verifikasi QR Code
    Route::get('/verifikasi/{hash}', [VerifikasiController::class, 'verify']);

    // Telegram Webhook
    Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle'])->middleware('throttle:60,1');
});

// Protected Routes - Shared Auth
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);
});

// Protected Routes - Warga
Route::prefix('v1')->middleware(['auth:sanctum', 'ability:warga'])->group(function () {

    // Auth
    Route::post('/auth/bind-telegram', [AuthController::class, 'bindTelegram']);

    // Pengajuan Surat
    Route::get('/surat/kategori', [PengajuanSuratController::class, 'kategori']);
    Route::get('/surat/kategori/{id}', [PengajuanSuratController::class, 'detailKategori']);
    Route::post('/surat/pengajuan', [PengajuanSuratController::class, 'store']);
    Route::get('/surat/pengajuan', [PengajuanSuratController::class, 'index']);
    Route::get('/surat/pengajuan/{id}', [PengajuanSuratController::class, 'show']);

    // Mutasi Penduduk
    Route::post('/mutasi', [MutasiPendudukController::class, 'store']);
    Route::get('/mutasi', [MutasiPendudukController::class, 'index']);
});

// Protected Routes - Admin Only
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'abilities:admin'])->group(function () {

    // Pengajuan Surat Management
    Route::get('/surat/pengajuan', [PengajuanSuratController::class, 'adminIndex']);
    Route::post('/surat/pengajuan/{id}/approve', [PengajuanSuratController::class, 'approve']);
    Route::post('/surat/pengajuan/{id}/reject', [PengajuanSuratController::class, 'reject']);

    // Mutasi Penduduk Management
    Route::get('/mutasi', [MutasiPendudukController::class, 'adminIndex']);
    Route::post('/mutasi/{id}/approve', [MutasiPendudukController::class, 'approve']);
    Route::post('/mutasi/{id}/reject', [MutasiPendudukController::class, 'reject']);

    // Informasi Publik Management
    Route::get('/informasi', [InformasiPublikController::class, 'adminIndex']);
    Route::post('/informasi', [InformasiPublikController::class, 'store']);
    Route::put('/informasi/{id}', [InformasiPublikController::class, 'update']);
    Route::delete('/informasi/{id}', [InformasiPublikController::class, 'destroy']);

    // Statistik Management
    Route::post('/statistik/clear-cache', [StatistikController::class, 'clearCache']);
});
