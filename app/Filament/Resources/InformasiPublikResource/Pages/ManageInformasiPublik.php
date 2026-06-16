<?php

namespace App\Filament\Resources\InformasiPublikResource\Pages;

use App\Filament\Resources\InformasiPublikResource;
use Filament\Resources\Pages\ManageRecords;

/**
 * Halaman manajemen artikel informasi publik gampong (tampilan utama).
 *
 * Menampilkan daftar artikel berita, pengumuman, dan informasi transparansi
 * dalam tampilan tabel dengan dukungan pencarian, filter, serta aksi CRUD
 * (buat, edit, hapus) tanpa perlu navigasi ke halaman terpisah.
 *
 * @see \App\Filament\Resources\InformasiPublikResource
 * @see \App\Models\InformasiPublik
 */
class ManageInformasiPublik extends ManageRecords
{
    protected static string $resource = InformasiPublikResource::class;
}
