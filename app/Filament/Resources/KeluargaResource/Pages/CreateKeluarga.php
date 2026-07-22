<?php

namespace App\Filament\Resources\KeluargaResource\Pages;

use App\Filament\Resources\KeluargaResource;
use App\Models\Keluarga;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

/**
 * Halaman pembuatan data Kartu Keluarga (KK).
 *
 * Menyediakan form untuk mencatat data KK baru, termasuk nomor KK,
 * kepala keluarga, dan alamat lengkap.
 *
 * @see \App\Filament\Resources\KeluargaResource
 * @see \App\Models\Keluarga
 */
class CreateKeluarga extends CreateRecord
{
    protected static string $resource = KeluargaResource::class;

    // ponytail: jika no_kk sudah ada (auto-create dari Penduduk), redirect ke edit
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $existing = Keluarga::where('no_kk', $data['no_kk'])->first();
        if ($existing) {
            Notification::make()
                ->title('No. KK Sudah Ada')
                ->body("Keluarga dengan nomor KK {$data['no_kk']} sudah terdaftar. Silakan lengkapi data yang kosong.")
                ->info()
                ->send();
            $this->redirect(KeluargaResource::getUrl('edit', ['record' => $existing]));
            $this->halt();
        }
        return $data;
    }
}
