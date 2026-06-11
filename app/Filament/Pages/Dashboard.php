<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard Administrasi';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';
}
