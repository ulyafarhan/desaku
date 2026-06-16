<?php

namespace App\Filament\Resources\PengaturanGampongResource\Pages;

use App\Filament\Resources\PengaturanGampongResource;
use Filament\Resources\Pages\ListRecords;

/**
 * Halaman daftar konfigurasi pengaturan gampong.
 *
 * Menampilkan seluruh parameter konfigurasi sistem desa dalam tabel
 * dengan dukungan pencarian dan aksi CRUD (buat, edit, hapus).
 *
 * @see \App\Filament\Resources\PengaturanGampongResource
 * @see \App\Models\PengaturanGampong
 */
class ListPengaturanGampong extends ListRecords
{
    protected static string $resource = PengaturanGampongResource::class;
}
