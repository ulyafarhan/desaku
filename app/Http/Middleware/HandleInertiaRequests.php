<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

/**
 * Middleware untuk membagikan data global secara dinamis dari backend Laravel ke frontend Vue via Inertia.js.
 */
class HandleInertiaRequests extends Middleware
{
    /**
     * @var string Root view yang digunakan untuk memuat aset frontend utama.
     */
    protected $rootView = 'app';

    /**
     * Mendefinisikan kumpulan data bersama (shared data) yang akan diakses di seluruh komponen Vue.
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'warga' => fn () => $request->user('penduduk'),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'settings' => [
                'nama_gampong' => \App\Models\PengaturanGampong::get('nama_gampong', 'Udeung'),
                'kecamatan' => \App\Models\PengaturanGampong::get('kecamatan', 'Bandar Baru'),
                'kabupaten' => \App\Models\PengaturanGampong::get('kabupaten', 'Pidie Jaya'),
                'provinsi' => \App\Models\PengaturanGampong::get('provinsi', 'Aceh'),
                'kode_pos' => \App\Models\PengaturanGampong::get('kode_pos', '24186'),
                'telepon' => \App\Models\PengaturanFrontend::get('telepon_operator') ?? \App\Models\PengaturanGampong::get('telepon', '-'),
                'email' => \App\Models\PengaturanGampong::get('email', '-'),
                'alamat' => \App\Models\PengaturanFrontend::get('alamat_kantor') ?? \App\Models\PengaturanGampong::get('alamat', '-'),
                'nama_keuchik' => \App\Models\PengaturanGampong::get('nama_keuchik', 'Nama Keuchik'),
                'nip_keuchik' => \App\Models\PengaturanGampong::get('nip_keuchik', ''),
                'nama_sekdes' => \App\Models\PengaturanFrontend::get('nama_sekdes') ?? \App\Models\PengaturanGampong::get('nama_sekdes', 'Nama Sekretaris Desa'),
                'nama_operator' => \App\Models\PengaturanFrontend::get('nama_operator') ?? \App\Models\PengaturanGampong::get('nama_operator', 'Nama Operator'),
                'medsos_facebook' => \App\Models\PengaturanFrontend::get('medsos_facebook') ?? \App\Models\PengaturanGampong::get('medsos_facebook', ''),
                'medsos_instagram' => \App\Models\PengaturanFrontend::get('medsos_instagram') ?? \App\Models\PengaturanGampong::get('medsos_instagram', ''),
                'medsos_twitter' => \App\Models\PengaturanFrontend::get('medsos_twitter') ?? \App\Models\PengaturanGampong::get('medsos_twitter', ''),
                'medsos_youtube' => \App\Models\PengaturanFrontend::get('medsos_youtube') ?? \App\Models\PengaturanGampong::get('medsos_youtube', ''),
                'tahun_anggaran' => \App\Models\PengaturanFrontend::get('tahun_anggaran') ?? \App\Models\PengaturanGampong::get('tahun_anggaran', date('Y')),
                'logo_gampong' => (\App\Models\PengaturanGampong::get('logo_gampong') && \Illuminate\Support\Facades\Storage::disk('public')->exists(\App\Models\PengaturanGampong::get('logo_gampong'))) ? \Illuminate\Support\Facades\Storage::url(\App\Models\PengaturanGampong::get('logo_gampong')) : '/logo.svg',
                'logo_fav' => (\App\Models\PengaturanGampong::get('logo_fav') && \Illuminate\Support\Facades\Storage::disk('public')->exists(\App\Models\PengaturanGampong::get('logo_fav'))) ? \Illuminate\Support\Facades\Storage::url(\App\Models\PengaturanGampong::get('logo_fav')) : '/logo-fav.svg',
                'banner_gampong' => (\App\Models\PengaturanGampong::get('banner_gampong') && \Illuminate\Support\Facades\Storage::disk('public')->exists(\App\Models\PengaturanGampong::get('banner_gampong'))) ? \Illuminate\Support\Facades\Storage::url(\App\Models\PengaturanGampong::get('banner_gampong')) : null,
            ],
        ];
    }
}
