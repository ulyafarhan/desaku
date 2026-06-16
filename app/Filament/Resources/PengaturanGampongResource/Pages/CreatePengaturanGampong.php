<?php

namespace App\Filament\Resources\PengaturanGampongResource\Pages;

use App\Filament\Resources\PengaturanGampongResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Halaman pembuatan konfigurasi pengaturan gampong.
 *
 * Menyediakan form untuk menambah parameter konfigurasi sistem desa,
 * termasuk kunci, tipe data, nilai, dan deskripsi.
 *
 * @see \App\Filament\Resources\PengaturanGampongResource
 * @see \App\Models\PengaturanGampong
 */
class CreatePengaturanGampong extends CreateRecord
{
    protected static string $resource = PengaturanGampongResource::class;
}
