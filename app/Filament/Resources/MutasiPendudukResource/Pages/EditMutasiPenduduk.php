<?php

namespace App\Filament\Resources\MutasiPendudukResource\Pages;

use App\Filament\Resources\MutasiPendudukResource;
use Filament\Resources\Pages\EditRecord;

class EditMutasiPenduduk extends EditRecord
{
    protected static string $resource = MutasiPendudukResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
