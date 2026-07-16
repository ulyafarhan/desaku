<?php

namespace App\Jobs;

use App\Models\Penduduk;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendStatusWhatsappJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public string $nik,
        public string $jenis,
        public string $status,
        public string $nomorRegistrasi,
        public ?string $catatan = null
    ) {}

    public function handle(WhatsAppService $whatsapp): void
    {
        $penduduk = Penduduk::find($this->nik);
        if (!$penduduk || empty($penduduk->no_hp)) {
            Log::warning("Warga NIK {$this->nik} tidak punya no_hp terdaftar.");
            return;
        }

        $statusLabels = [
            'Pending'   => 'Menunggu Verifikasi',
            'Diproses'  => 'Sedang Diproses',
            'Disetujui' => 'Disetujui',
            'Ditolak'   => 'Ditolak',
            'Selesai'   => 'Selesai, Siap Diunduh',
        ];

        $message = "*STATUS PENGAJUAN SURAT*\n\n";
        $message .= "Jenis: " . $this->jenis . "\n";
        $message .= "Nomor: " . $this->nomorRegistrasi . "\n";
        $message .= "Status: " . ($statusLabels[$this->status] ?? $this->status) . "\n\n";

        if ($this->catatan) {
            $message .= "Catatan:\n" . $this->catatan . "\n\n";
        }

        $message .= config('app.url');

        $whatsapp->sendMessage($penduduk->no_hp, $message);
    }
}