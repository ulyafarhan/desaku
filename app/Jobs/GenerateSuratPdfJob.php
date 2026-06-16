<?php

namespace App\Jobs;

use App\Models\PengajuanSurat;
use App\Models\TrackingPengajuanSurat;
use App\Services\PdfGeneratorService;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job untuk memproses pembuatan tanda tangan digital (hash QR) dan pembaruan status surat menjadi Selesai.
 */
class GenerateSuratPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Inisialisasi Job dengan objek pengajuan surat.
     */
    public function __construct(
        public PengajuanSurat $pengajuan
    ) {}

    /**
     * Mengeksekusi pembuatan tanda tangan digital dan notifikasi ke warga via Telegram.
     */
    public function handle(
        PdfGeneratorService $pdfService,
        TelegramService $telegram
    ): void {
        try {
            if (empty($this->pengajuan->qr_hash)) {
                $qrHash = hash('sha256', implode('|', [
                    $this->pengajuan->nomor_registrasi,
                    $this->pengajuan->nik_pemohon,
                    $this->pengajuan->kategori->kode_surat ?? 'SRT',
                    $this->pengajuan->created_at?->timestamp ?? time(),
                ]));
                $this->pengajuan->update(['qr_hash' => $qrHash]);
            }

            $printUrl = "/warga/pengajuan/{$this->pengajuan->id}/print";

            $this->pengajuan->update([
                'status' => 'Selesai',
                'file_pdf_url' => $printUrl
            ]);
            
            TrackingPengajuanSurat::create([
                'pengajuan_surat_id' => $this->pengajuan->id,
                'status_sebelumnya' => 'Disetujui',
                'status_baru' => 'Selesai',
                'keterangan_update' => 'Surat selesai diproses dan siap dicetak',
            ]);

            $pemohon = $this->pengajuan->pemohon;
            
            if ($pemohon && $pemohon->telegram_chat_id) {
                $message = "<b>Surat Anda telah selesai!</b>\n\n";
                $message .= "Nomor: <code>{$this->pengajuan->nomor_registrasi}</code>\n";
                $message .= "Jenis: {$this->pengajuan->kategori->nama_surat}\n\n";
                $message .= "Anda dapat melihat dan mengunduh/mencetak surat di:\n";
                $message .= config('app.url') . "/warga/dashboard?tab=pengajuan";

                $telegram->sendMessage(
                    $pemohon->telegram_chat_id,
                    $message
                );
            }

            $telegram->notifyPengajuanStatus(
                $this->pengajuan->nik_pemohon,
                'Selesai',
                $this->pengajuan->nomor_registrasi
            );

            Log::info("Letter processed successfully for pengajuan #{$this->pengajuan->id}");

        } catch (\Exception $e) {
            Log::error("Failed to process letter for pengajuan #{$this->pengajuan->id}: " . $e->getMessage());
            
            $this->pengajuan->update([
                'status' => 'Ditolak',
                'catatan_penolakan' => 'Gagal memproses surat: ' . $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
