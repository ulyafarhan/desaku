<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StatistikService;

class StatistikController extends Controller
{
    public function __construct(
        protected StatistikService $statistik
    ) {}

    public function demografi()
    {
        $data = $this->statistik->getDemografi();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function layanan()
    {
        $data = $this->statistik->getLayanan();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function clearCache()
    {
        $this->statistik->clearCache();

        return response()->json([
            'message' => 'Cache statistik berhasil dibersihkan',
        ]);
    }
}
