<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard Administrasi';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    public function getSubheading(): ?string
    {
        $admin = auth('admin')->user();
        $greeting = match (true) {
            now()->hour < 12 => 'Selamat Pagi',
            now()->hour < 15 => 'Selamat Siang',
            now()->hour < 18 => 'Selamat Sore',
            default => 'Selamat Malam',
        };

        return $greeting . ', ' . ($admin?->username ?? 'Admin') . '! Berikut ringkasan data desa terkini.';
    }
}
