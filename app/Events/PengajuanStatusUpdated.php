<?php

namespace App\Events;

use App\Models\PengajuanSurat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PengajuanStatusUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public PengajuanSurat $pengajuan,
        public string $oldStatus,
        public string $newStatus,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('pengajuan'),
            new Channel('warga.' . $this->pengajuan->nik_pemohon),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->pengajuan->id,
            'nomor_registrasi' => $this->pengajuan->nomor_registrasi,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'nama_surat' => $this->pengajuan->kategori?->nama_surat,
            'nama_pemohon' => $this->pengajuan->pemohon?->nama_lengkap,
        ];
    }
}
