<?php

namespace App\Filament\Widgets;

use App\Models\InformasiPublik;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Penduduk aktif', Penduduk::query()->aktif()->count())
                ->description('Warga dengan status tetap'),
            Stat::make('Pengajuan pending', PengajuanSurat::query()->pending()->count())
                ->description('Menunggu verifikasi petugas'),
            Stat::make('Informasi terbit', InformasiPublik::query()->published()->count())
                ->description('Berita dan pengumuman aktif'),
        ];
    }
}
