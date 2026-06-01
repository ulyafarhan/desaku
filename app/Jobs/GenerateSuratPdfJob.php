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

class GenerateSuratPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public PengajuanSurat $pengajuan
    ) {}

    public function handle(
        PdfGeneratorService $pdfService,
        TelegramService $telegram
    ): void {
        try {
            // Update status ke Diproses
            $this->pengajuan->update(['status' => 'Diproses']);
            
            TrackingPengajuanSurat::create([
                'pengajuan_surat_id' => $this->pengajuan->id,
                'status_sebelumnya' => 'Disetujui',
                'status_baru' => 'Diproses',
                'keterangan_update' => 'Sedang generate PDF',
            ]);

            // Generate PDF
            $pdfUrl = $pdfService->generateSuratPdf($this->pengajuan);

            // Update status ke Selesai
            $this->pengajuan->update(['status' => 'Selesai']);
            
            TrackingPengajuanSurat::create([
                'pengajuan_surat_id' => $this->pengajuan->id,
                'status_sebelumnya' => 'Diproses',
                'status_baru' => 'Selesai',
                'keterangan_update' => 'PDF berhasil dibuat',
            ]);

            // Kirim PDF via Telegram
            $pemohon = $this->pengajuan->pemohon;
            
            if ($pemohon->telegram_chat_id) {
                $caption = "✅ Surat Anda telah selesai!\n\n";
                $caption .= "Nomor: {$this->pengajuan->nomor_registrasi}\n";
                $caption .= "Jenis: {$this->pengajuan->kategori->nama_surat}";

                $telegram->sendDocument(
                    $pemohon->telegram_chat_id,
                    storage_path('app/public/' . str_replace('/storage/', '', $pdfUrl)),
                    $caption
                );
            }

            // Notifikasi status selesai
            $telegram->notifyPengajuanStatus(
                $this->pengajuan->nik_pemohon,
                'Selesai',
                $this->pengajuan->nomor_registrasi
            );

            Log::info("PDF generated successfully for pengajuan #{$this->pengajuan->id}");

        } catch (\Exception $e) {
            Log::error("Failed to generate PDF for pengajuan #{$this->pengajuan->id}: " . $e->getMessage());
            
            $this->pengajuan->update([
                'status' => 'Ditolak',
                'catatan_penolakan' => 'Gagal generate PDF: ' . $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
