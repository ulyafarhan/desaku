<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StatistikService;

/**
 * Controller untuk mengelola pengambilan data statistik kependudukan dan layanan gampong lewat API.
 */
class StatistikController extends Controller
{
    /**
     * Menginjeksi dependensi StatistikService.
     */
    public function __construct(
        protected StatistikService $statistik
    ) {}

    /**
     * Mengambil rangkuman data demografi kependudukan gampong.
     */
    public function demografi()
    {
        $data = $this->statistik->getDemografi();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Mengambil rangkuman data statistik statistik layanan administrasi persuratan.
     */
    public function layanan()
    {
        $data = $this->statistik->getLayanan();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Membersihkan cache penyimpanan data statistik.
     */
    public function clearCache()
    {
        $this->statistik->clearCache();

        return response()->json([
            'message' => 'Cache statistik berhasil dibersihkan',
        ]);
    }
}
