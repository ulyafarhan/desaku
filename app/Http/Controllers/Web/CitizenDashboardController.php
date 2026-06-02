<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\KategoriSurat;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CitizenDashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $warga = $request->user('penduduk');

        return Inertia::render('Citizen/Dashboard', [
            'warga' => $warga->load('keluarga'),
            'kategoriSurat' => KategoriSurat::query()
                ->active()
                ->orderBy('nama_surat')
                ->get(),
            'pengajuan' => PengajuanSurat::query()
                ->where('nik_pemohon', $warga->nik)
                ->with(['kategori:id,nama_surat,kode_surat', 'tracking'])
                ->latest()
                ->paginate(6),
            'summary' => [
                'pending' => PengajuanSurat::query()->where('nik_pemohon', $warga->nik)->where('status', 'Pending')->count(),
                'diproses' => PengajuanSurat::query()->where('nik_pemohon', $warga->nik)->where('status', 'Diproses')->count(),
                'selesai' => PengajuanSurat::query()->where('nik_pemohon', $warga->nik)->whereIn('status', ['Disetujui', 'Selesai'])->count(),
            ],
        ]);
    }
}
