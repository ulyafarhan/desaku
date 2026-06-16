<?php

namespace App\Filament\Resources\PengaturanGampongResource\Pages;

use App\Filament\Resources\PengaturanGampongResource;
use Filament\Resources\Pages\EditRecord;

/**
 * Halaman edit konfigurasi pengaturan gampong.
 *
 * Menyediakan form untuk mengubah parameter konfigurasi sistem desa,
 * termasuk kunci, tipe data, nilai, dan deskripsi.
 *
 * @see \App\Filament\Resources\PengaturanGampongResource
 * @see \App\Models\PengaturanGampong
 */
class EditPengaturanGampong extends EditRecord
{
    protected static string $resource = PengaturanGampongResource::class;
}
