<?php

namespace App\Services;

use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\PengajuanSurat;
use App\Models\MutasiPenduduk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class StatistikService
{
    /**
     * Get statistik demografi real-time
     */
    public function getDemografi(): array
    {
        return Cache::remember('statistik_demografi', 3600, function () {
            $totalPenduduk = Penduduk::aktif()->count();
            $totalKeluarga = Keluarga::count();

            return [
                'total_penduduk' => $totalPenduduk,
                'total_keluarga' => $totalKeluarga,
                'laki_laki' => Penduduk::aktif()->where('jenis_kelamin', 'L')->count(),
                'perempuan' => Penduduk::aktif()->where('jenis_kelamin', 'P')->count(),
                'per_dusun' => $this->getPerDusun(),
                'per_agama' => $this->getPerAgama(),
                'per_pendidikan' => $this->getPerPendidikan(),
                'per_pekerjaan' => $this->getPerPekerjaan(),
                'per_usia' => $this->getPerKelompokUsia(),
            ];
        });
    }

    protected function getPerDusun(): array
    {
        return Keluarga::select('dusun', DB::raw('count(*) as jumlah'))
            ->groupBy('dusun')
            ->get()
            ->pluck('jumlah', 'dusun')
            ->toArray();
    }

    protected function getPerAgama(): array
    {
        return Penduduk::aktif()
            ->select('agama', DB::raw('count(*) as jumlah'))
            ->groupBy('agama')
            ->get()
            ->pluck('jumlah', 'agama')
            ->toArray();
    }

    protected function getPerPendidikan(): array
    {
        return Penduduk::aktif()
            ->select('pendidikan', DB::raw('count(*) as jumlah'))
            ->groupBy('pendidikan')
            ->get()
            ->pluck('jumlah', 'pendidikan')
            ->toArray();
    }

    protected function getPerPekerjaan(): array
    {
        return Penduduk::aktif()
            ->select('pekerjaan', DB::raw('count(*) as jumlah'))
            ->groupBy('pekerjaan')
            ->orderByDesc('jumlah')
            ->limit(10)
            ->get()
            ->pluck('jumlah', 'pekerjaan')
            ->toArray();
    }

    protected function getPerKelompokUsia(): array
    {
        $penduduk = Penduduk::aktif()->get();

        $kelompok = [
            '0-5' => 0,
            '6-12' => 0,
            '13-17' => 0,
            '18-25' => 0,
            '26-40' => 0,
            '41-60' => 0,
            '60+' => 0,
        ];

        foreach ($penduduk as $p) {
            $umur = $p->umur;
            
            if ($umur <= 5) $kelompok['0-5']++;
            elseif ($umur <= 12) $kelompok['6-12']++;
            elseif ($umur <= 17) $kelompok['13-17']++;
            elseif ($umur <= 25) $kelompok['18-25']++;
            elseif ($umur <= 40) $kelompok['26-40']++;
            elseif ($umur <= 60) $kelompok['41-60']++;
            else $kelompok['60+']++;
        }

        return $kelompok;
    }

    /**
     * Get statistik layanan
     */
    public function getLayanan(): array
    {
        return [
            'pengajuan_surat' => [
                'total' => PengajuanSurat::count(),
                'pending' => PengajuanSurat::where('status', 'Pending')->count(),
                'diproses' => PengajuanSurat::where('status', 'Diproses')->count(),
                'selesai' => PengajuanSurat::where('status', 'Selesai')->count(),
                'ditolak' => PengajuanSurat::where('status', 'Ditolak')->count(),
            ],
            'mutasi_penduduk' => [
                'total' => MutasiPenduduk::count(),
                'pending' => MutasiPenduduk::where('status_verifikasi', 'Pending')->count(),
                'disetujui' => MutasiPenduduk::where('status_verifikasi', 'Disetujui')->count(),
                'ditolak' => MutasiPenduduk::where('status_verifikasi', 'Ditolak')->count(),
            ],
            'per_jenis_surat' => $this->getPerJenisSurat(),
        ];
    }

    protected function getPerJenisSurat(): array
    {
        return PengajuanSurat::select('kategori_surat_id', DB::raw('count(*) as jumlah'))
            ->with('kategori:id,nama_surat')
            ->groupBy('kategori_surat_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->kategori->nama_surat => $item->jumlah];
            })
            ->toArray();
    }

    /**
     * Clear cache statistik
     */
    public function clearCache(): void
    {
        Cache::forget('statistik_demografi');
    }
}
