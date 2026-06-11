<?php

namespace App\Services;

use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PdfGeneratorService
{
    public function generateSuratPdf(PengajuanSurat $pengajuan): string
    {
        // Generate QR Hash
        $qrHash = $this->generateQrHash($pengajuan);
        $pengajuan->update(['qr_hash' => $qrHash]);

        // Generate QR Code Image
        $qrCodePath = $this->generateQrCode($qrHash);

        // Ensure Logo is present
        $this->ensureLogoDownloaded();

        // Prepare data untuk template
        $data = [
            'pengajuan' => $pengajuan,
            'pemohon' => $pengajuan->pemohon,
            'kategori' => $pengajuan->kategori,
            'data_isian' => $pengajuan->data_isian,
            'qr_code_path' => $qrCodePath,
            'nomor_surat' => $this->generateNomorSurat($pengajuan),
            'tanggal_surat' => now()->locale('id')->isoFormat('D MMMM YYYY'),
        ];

        // Render HTML dari blade template
        $html = view('pdf.surat.' . $pengajuan->kategori->template_view, $data)->render();

        // Generate PDF menggunakan library (contoh: DomPDF atau Snappy)
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        
        // Save PDF
        $filename = 'surat_' . $pengajuan->nomor_registrasi . '_' . time() . '.pdf';
        $pdfPath = 'surat/' . $filename;
        
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Update pengajuan dengan URL PDF
        $pdfUrl = Storage::disk('public')->url($pdfPath);
        $pengajuan->update(['file_pdf_url' => $pdfUrl]);

        return $pdfUrl;
    }

    protected function generateQrHash(PengajuanSurat $pengajuan): string
    {
        $data = implode('|', [
            $pengajuan->nomor_registrasi,
            $pengajuan->nik_pemohon,
            $pengajuan->kategori->kode_surat,
            $pengajuan->created_at->timestamp,
        ]);

        return hash('sha256', $data);
    }

    protected function generateQrCode(string $hash): string
    {
        // Generate QR Code menggunakan library (contoh: SimpleSoftwareIO/simple-qrcode)
        $verificationUrl = config('app.url') . '/verifikasi/' . $hash;
        
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(200)
            ->generate($verificationUrl);

        $filename = 'qr_' . $hash . '.svg';
        $path = 'qrcodes/' . $filename;
        
        Storage::disk('public')->put($path, $qrCode);

        return Storage::disk('public')->path($path);
    }

    protected function ensureLogoDownloaded(): void
    {
        $logoDir = public_path('images');
        $logoPath = $logoDir . '/logo-gampong.png';
        if (!file_exists($logoPath)) {
            if (!is_dir($logoDir)) {
                @mkdir($logoDir, 0777, true);
            }
            $opts = [
                'http' => [
                    'method' => 'GET',
                    'header' => "User-Agent: DesakuApp/1.0 (http://desaku.test; admin@desaku.test) PHP/8.3\r\n"
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ];
            $context = stream_context_create($opts);
            $logoContent = @file_get_contents('https://upload.wikimedia.org/wikipedia/commons/4/42/Lambang_Kabupaten_Pidie_Jaya.png', false, $context);
            if ($logoContent !== false) {
                @file_put_contents($logoPath, $logoContent);
            }
        }
    }

    protected function generateNomorSurat(PengajuanSurat $pengajuan): string
    {
        $counter = PengajuanSurat::where('kategori_surat_id', $pengajuan->kategori_surat_id)
            ->whereYear('created_at', date('Y'))
            ->count();

        return sprintf(
            '%s/%03d/GAMPONG-UDEUNG/%s/%s',
            $pengajuan->kategori->kode_surat,
            $counter,
            strtoupper(now()->locale('id')->monthName),
            date('Y')
        );
    }
}
