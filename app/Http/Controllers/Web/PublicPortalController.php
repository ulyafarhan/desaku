<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\InformasiPublik;
use App\Models\KategoriSurat;
use App\Models\PengajuanSurat;
use App\Services\StatistikService;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller untuk mengelola halaman publik dan layanan portal umum.
 */
class PublicPortalController extends Controller
{
    /**
     * Menyajikan halaman beranda publik dengan statistik gampong dan berita terbaru.
     */
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

    /**
     * Menyajikan halaman profil gampong dan daftar perangkat desa.
     */
    public function profile(): Response
    {
        $getFotoUrl = function ($kunci, $fallbackKey = null) {
            $val = \App\Models\PengaturanFrontend::get($kunci);
            if (!$val && $fallbackKey) {
                $val = \App\Models\PengaturanGampong::get($fallbackKey);
            }
            if (empty($val)) {
                return '/images/default-avatar.png';
            }
            if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://') || str_starts_with($val, '/images/')) {
                return $val;
            }
            return \Illuminate\Support\Facades\Storage::url($val);
        };

        return Inertia::render('Public/Profile', [
            'perangkat' => [
                [
                    'jabatan' => 'Keuchik',
                    'nama' => \App\Models\PengaturanGampong::get('nama_keuchik', 'Nama Keuchik'),
                    'foto' => $getFotoUrl('foto_keuchik', 'foto_keuchik'),
                ],
                [
                    'jabatan' => 'Sekretaris Desa',
                    'nama' => \App\Models\PengaturanFrontend::get('nama_sekdes') ?? \App\Models\PengaturanGampong::get('nama_sekdes', 'Nama Sekretaris Desa'),
                    'foto' => $getFotoUrl('foto_sekdes', 'foto_sekdes'),
                ],
                [
                    'jabatan' => 'Operator Layanan',
                    'nama' => \App\Models\PengaturanFrontend::get('nama_operator') ?? \App\Models\PengaturanGampong::get('nama_operator', 'Nama Operator'),
                    'foto' => $getFotoUrl('foto_operator', 'foto_operator'),
                ],
            ],
        ]);
    }

    /**
     * Menyajikan halaman indeks kumpulan informasi publik/berita/pengumuman gampong.
     */
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

    /**
     * Menyajikan detail dari suatu berita/pengumuman publik berdasarkan slug.
     */
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

    /**
     * Menyajikan halaman beranda verifikasi keabsahan surat lewat QR Code.
     */
    public function verifyIndex(): Response
    {
        return Inertia::render('Public/Verify', [
            'result' => null
        ]);
    }

    /**
     * Memproses verifikasi detail data surat berdasarkan hash tanda tangan elektronik.
     */
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

    /**
     * Menyajikan halaman visualisasi data demografi dan statistik layanan gampong.
     */
    public function statistik(StatistikService $statistik): Response
    {
        return Inertia::render('Public/Statistik', [
            'demografi' => $statistik->getDemografi(),
            'layanan' => $statistik->getLayanan(),
        ]);
    }
}
