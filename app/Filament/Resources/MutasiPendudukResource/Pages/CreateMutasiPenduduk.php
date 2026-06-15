<?php

namespace App\Filament\Resources\MutasiPendudukResource\Pages;

use App\Filament\Resources\MutasiPendudukResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMutasiPenduduk extends CreateRecord
{
    protected static string $resource = MutasiPendudukResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
