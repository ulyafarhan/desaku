<?php

namespace App\Filament\Resources\PengaturanGampongResource\Pages;

use App\Filament\Resources\PengaturanGampongResource;
use Filament\Resources\Pages\ManageRecords;

/**
 * Halaman manajemen konfigurasi pengaturan gampong.
 *
 * Menampilkan seluruh parameter konfigurasi sistem desa dalam tampilan
 * tabel dengan aksi CRUD terintegrasi (buat, edit, hapus) tanpa navigasi
 * halaman terpisah.
 *
 * @see \App\Filament\Resources\PengaturanGampongResource
 * @see \App\Models\PengaturanGampong
 */
class ManagePengaturanGampong extends ManageRecords
{
    protected static string $resource = PengaturanGampongResource::class;
}
