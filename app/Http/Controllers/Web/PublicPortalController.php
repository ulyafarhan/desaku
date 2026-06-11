<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\InformasiPublik;
use App\Models\KategoriSurat;
use App\Models\PengajuanSurat;
use App\Services\StatistikService;
use Inertia\Inertia;
use Inertia\Response;

class PublicPortalController extends Controller
{
    public function home(StatistikService $statistik): Response
    {
        return Inertia::render('Public/Home', [
            'demografi' => $statistik->getDemografi(),
            'layanan' => $statistik->getLayanan(),
            'berita' => InformasiPublik::query()
                ->published()
                ->with('author:id,username')
                ->latest('created_at')
                ->limit(3)
                ->get(),
            'kategoriSurat' => KategoriSurat::query()
                ->active()
                ->orderBy('nama_surat')
                ->limit(6)
                ->get(['id', 'kode_surat', 'nama_surat']),
        ]);
    }

    public function profile(): Response
    {
        return Inertia::render('Public/Profile', [
            'perangkat' => [
                ['jabatan' => 'Keuchik', 'nama' => 'Pemerintah Gampong'],
                ['jabatan' => 'Sekretaris Desa', 'nama' => 'Sekretariat Gampong'],
                ['jabatan' => 'Operator Layanan', 'nama' => 'Pelayanan Administrasi'],
            ],
        ]);
    }

    public function information(): Response
    {
        $query = InformasiPublik::query()
            ->published()
            ->with('author:id,username')
            ->latest('created_at');

        $query->when(request('kategori'), function ($q, $kategori) {
            return $q->where('kategori', $kategori);
        });

        $query->when(request('search'), function ($q, $search) {
            return $q->where(function ($sub) use ($search) {
                $sub->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('konten', 'like', '%' . $search . '%');
            });
        });

        return Inertia::render('Public/Information/Index', [
            'informasi' => $query->paginate(9)->withQueryString(),
            'kategori' => InformasiPublik::query()
                ->published()
                ->select('kategori')
                ->distinct()
                ->orderBy('kategori')
                ->pluck('kategori'),
            'filters' => request()->only(['kategori', 'search']),
        ]);
    }

    public function informationShow(string $slug): Response
    {
        return Inertia::render('Public/Information/Show', [
            'informasi' => InformasiPublik::query()
                ->published()
                ->with('author:id,username')
                ->where('slug', $slug)
                ->firstOrFail(),
        ]);
    }

    public function verifyIndex(): Response
    {
        return Inertia::render('Public/Verify', [
            'result' => null
        ]);
    }

    public function verify(string $hash): Response
    {
        $pengajuan = PengajuanSurat::query()
            ->with(['kategori:id,nama_surat', 'pemohon:nik,nama_lengkap', 'verifikator:id,username'])
            ->where('qr_hash', $hash)
            ->first();

        return Inertia::render('Public/Verify', [
            'result' => $pengajuan && $pengajuan->status === 'Selesai'
                ? [
                    'valid' => true,
                    'message' => 'Dokumen terverifikasi.',
                    'nomor_registrasi' => $pengajuan->nomor_registrasi,
                    'jenis_surat' => $pengajuan->kategori?->nama_surat,
                    'nama_pemohon' => $pengajuan->pemohon?->nama_lengkap,
                    'nik_pemohon' => $pengajuan->nik_pemohon,
                    'tanggal_terbit' => $pengajuan->updated_at?->format('d-m-Y'),
                    'diverifikasi_oleh' => $pengajuan->verifikator?->username,
                ]
                : [
                    'valid' => false,
                    'message' => $pengajuan ? 'Dokumen belum selesai diproses.' : 'Dokumen tidak ditemukan atau tidak valid.',
                ],
        ]);
    }

    public function statistik(StatistikService $statistik): Response
    {
        return Inertia::render('Public/Statistik', [
            'demografi' => $statistik->getDemografi(),
            'layanan' => $statistik->getLayanan(),
        ]);
    }
}
