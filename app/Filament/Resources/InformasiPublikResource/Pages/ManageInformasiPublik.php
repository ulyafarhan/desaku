<?php

namespace App\Filament\Resources\InformasiPublikResource\Pages;

use App\Filament\Resources\InformasiPublikResource;
use Filament\Resources\Pages\ManageRecords;

class ManageInformasiPublik extends ManageRecords
{
    protected static string $resource = InformasiPublikResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['cover_image_file'])) {
            $data['cover_image'] = $data['cover_image_file'];
        } elseif (!empty($data['cover_image_url'])) {
            $data['cover_image'] = $data['cover_image_url'];
        } else {
            $data['cover_image'] = null;
        }
        unset($data['cover_image_file'], $data['cover_image_url']);
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['cover_image_file'])) {
            $data['cover_image'] = $data['cover_image_file'];
        } elseif (!empty($data['cover_image_url'])) {
            $data['cover_image'] = $data['cover_image_url'];
        } else {
            $data['cover_image'] = null;
        }
        unset($data['cover_image_file'], $data['cover_image_url']);
        return $data;
    }
}
