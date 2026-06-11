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
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'sqlite') {
            $ageSql = "(strftime('%Y', 'now') - strftime('%Y', tanggal_lahir))";
        } elseif ($driver === 'mysql') {
            $ageSql = "(YEAR(CURDATE()) - YEAR(tanggal_lahir) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(tanggal_lahir, '%m%d')))";
        } else {
            $ageSql = "EXTRACT(YEAR FROM AGE(tanggal_lahir))";
        }

        $results = Penduduk::aktif()
            ->select(DB::raw("
                COUNT(CASE WHEN $ageSql <= 5 THEN 1 END) as age_0_5,
                COUNT(CASE WHEN $ageSql > 5 AND $ageSql <= 12 THEN 1 END) as age_6_12,
                COUNT(CASE WHEN $ageSql > 12 AND $ageSql <= 17 THEN 1 END) as age_13_17,
                COUNT(CASE WHEN $ageSql > 17 AND $ageSql <= 25 THEN 1 END) as age_18_25,
                COUNT(CASE WHEN $ageSql > 25 AND $ageSql <= 40 THEN 1 END) as age_26_40,
                COUNT(CASE WHEN $ageSql > 40 AND $ageSql <= 60 THEN 1 END) as age_41_60,
                COUNT(CASE WHEN $ageSql > 60 THEN 1 END) as age_60_plus
            "))
            ->first();

        return [
            '0-5' => (int) ($results->age_0_5 ?? 0),
            '6-12' => (int) ($results->age_6_12 ?? 0),
            '13-17' => (int) ($results->age_13_17 ?? 0),
            '18-25' => (int) ($results->age_18_25 ?? 0),
            '26-40' => (int) ($results->age_26_40 ?? 0),
            '41-60' => (int) ($results->age_41_60 ?? 0),
            '60+' => (int) ($results->age_60_plus ?? 0),
        ];
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
