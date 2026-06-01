<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StatistikService;

/**
 * @group Statistik
 * 
 * APIs untuk mendapatkan statistik demografi dan layanan gampong
 */
class StatistikController extends Controller
{
    public function __construct(
        protected StatistikService $statistik
    ) {}

    /**
     * Statistik Demografi
     * 
     * Mendapatkan statistik demografi penduduk (jenis kelamin, agama, pendidikan, pekerjaan, dll).
     * Data di-cache selama 1 jam untuk performa optimal.
     * 
     * @response 200 {
     *   "data": {
     *     "total_penduduk": 1250,
     *     "total_keluarga": 320,
     *     "jenis_kelamin": {
     *       "L": 625,
     *       "P": 625
     *     },
     *     "agama": {
     *       "Islam": 1200,
     *       "Kristen": 30,
     *       "Katolik": 20
     *     },
     *     "pendidikan": {
     *       "SD": 300,
     *       "SMP": 250,
     *       "SMA": 400,
     *       "S1": 200,
     *       "S2": 50
     *     },
     *     "pekerjaan": {
     *       "Petani": 400,
     *       "Wiraswasta": 300,
     *       "PNS": 150,
     *       "Pelajar": 250
     *     },
     *     "status_perkawinan": {
     *       "Belum Kawin": 500,
     *       "Kawin": 650,
     *       "Cerai Hidup": 50,
     *       "Cerai Mati": 50
     *     }
     *   }
     * }
     */
    public function demografi()
    {
        $data = $this->statistik->getDemografi();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Statistik Layanan
     * 
     * Mendapatkan statistik layanan pengajuan surat dan mutasi penduduk.
     * Data di-cache selama 1 jam untuk performa optimal.
     * 
     * @response 200 {
     *   "data": {
     *     "pengajuan_surat": {
     *       "total": 150,
     *       "pending": 10,
     *       "diproses": 5,
     *       "disetujui": 120,
     *       "ditolak": 10,
     *       "selesai": 115,
     *       "per_kategori": {
     *         "Surat Keterangan Domisili": 80,
     *         "Surat Keterangan Tidak Mampu": 70
     *       }
     *     },
     *     "mutasi_penduduk": {
     *       "total": 25,
     *       "pending": 3,
     *       "disetujui": 20,
     *       "ditolak": 2,
     *       "per_jenis": {
     *         "Kelahiran": 10,
     *         "Kematian": 5,
     *         "Kedatangan": 7,
     *         "Kepindahan": 3
     *       }
     *     }
     *   }
     * }
     */
    public function layanan()
    {
        $data = $this->statistik->getLayanan();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * [Admin] Clear Cache Statistik
     * 
     * Membersihkan cache statistik untuk refresh data (admin only).
     * 
     * @authenticated
     * 
     * @response 200 {
     *   "message": "Cache statistik berhasil dibersihkan"
     * }
     */
    public function clearCache()
    {
        $this->statistik->clearCache();

        return response()->json([
            'message' => 'Cache statistik berhasil dibersihkan',
        ]);
    }
}
