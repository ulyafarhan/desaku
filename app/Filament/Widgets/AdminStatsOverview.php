<?php

namespace App\Filament\Widgets;

use App\Models\InformasiPublik;
use App\Models\Keluarga;
use App\Models\MutasiPenduduk;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '10s';

    protected $listeners = [
        'echo:pengajuan,PengajuanStatusUpdated' => '$refresh',
        'echo:mutasi,MutasiStatusUpdated' => '$refresh',
    ];

    protected function getStats(): array
    {
        $pendudukAktif = Penduduk::query()->aktif()->count();
        $totalKeluarga = Keluarga::query()->count();
        $pengajuanPending = PengajuanSurat::query()->pending()->count();
        $pengajuanTotal = PengajuanSurat::query()->count();
        $mutasiPending = MutasiPenduduk::query()->where('status_verifikasi', 'Pending')->count();
        $informasiTerbit = InformasiPublik::query()->published()->count();

        return [
            Stat::make('Penduduk Aktif', $pendudukAktif)
                ->description('Warga dengan status tetap')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, $pendudukAktif > 6 ? 3 : 1, 5]),

            Stat::make('Total Keluarga', $totalKeluarga)
                ->description('Kartu keluarga terdaftar')
                ->descriptionIcon('heroicon-m-home')
                ->color('info')
                ->chart([3, 5, 2, 4, 6, 3, $totalKeluarga > 0 ? 4 : 1]),

            Stat::make('Pengajuan Pending', $pengajuanPending)
                ->description($pengajuanTotal . ' total pengajuan')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pengajuanPending > 0 ? 'warning' : 'success')
                ->chart([2, 4, 6, 3, $pengajuanPending, 4, 2]),

            Stat::make('Mutasi Pending', $mutasiPending)
                ->description('Menunggu verifikasi')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color($mutasiPending > 0 ? 'warning' : 'success')
                ->chart([1, 3, 2, $mutasiPending, 2, 3, 1]),

            Stat::make('Informasi Terbit', $informasiTerbit)
                ->description('Berita dan pengumuman aktif')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success')
                ->chart([2, 3, 4, 3, $informasiTerbit > 0 ? 5 : 1, 4, 3]),
        ];
    }
}
