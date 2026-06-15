<?php
namespace App\Filament\Resources\PendudukResource\Pages;
use App\Filament\Resources\PendudukResource;
use Filament\Resources\Pages\EditRecord;
class EditPenduduk extends EditRecord
{
    protected static string $resource = PendudukResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
