<?php

namespace App\Filament\Pages;

use App\Models\InformasiPublik;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class Dashboard extends BaseDashboard
{
    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Html::make(fn () => new HtmlString(view('filament.dashboard-cards', [
                'pendudukAktif' => Penduduk::query()->aktif()->count(),
                'pengajuanPending' => PengajuanSurat::query()->pending()->count(),
                'informasiTerbit' => InformasiPublik::query()->published()->count(),
            ])->render())),
        ]);
    }
}
