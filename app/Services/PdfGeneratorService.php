<?php

namespace App\Services;

use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Service untuk menghasilkan dokumen PDF surat resmi desa.
 *
 * Membuat PDF dari template Blade, menambahkan QR Code, nomor surat,
 * dan menyimpannya ke storage publik.
 */
class PdfGeneratorService
{
    /**
     * Menghasilkan PDF surat untuk pengajuan yang telah disetujui.
     *
     * Membuat QR hash, QR code, nomor surat, merender template Blade,
     * dan menyimpan hasil PDF ke storage.
     */
    public function generateSuratPdf(PengajuanSurat $pengajuan): string
    {
        $qrHash = $this->generateQrHash($pengajuan);
        $pengajuan->update(['qr_hash' => $qrHash]);

        $qrCodePath = $this->generateQrCode($qrHash);

        $this->ensureLogoDownloaded();

        $data = [
            'pengajuan' => $pengajuan,
            'pemohon' => $pengajuan->pemohon,
            'kategori' => $pengajuan->kategori,
            'data_isian' => $pengajuan->data_isian,
            'qr_code_path' => $qrCodePath,
            'nomor_surat' => $this->generateNomorSurat($pengajuan),
            'tanggal_surat' => now()->locale('id')->isoFormat('D MMMM YYYY'),
        ];

        $html = view('pdf.surat.' . $pengajuan->kategori->template_view, $data)->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        
        $filename = 'surat_' . $pengajuan->nomor_registrasi . '_' . time() . '.pdf';
        $pdfPath = 'surat/' . $filename;
        
        Storage::disk('public')->put($pdfPath, $pdf->output());

        $pdfUrl = Storage::disk('public')->url($pdfPath);
        $pengajuan->update(['file_pdf_url' => $pdfUrl]);

        return $pdfUrl;
    }

    /**
     * Menghasilkan hash SHA-256 dari data pengajuan untuk QR code.
     */
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

    /**
     * Membuat gambar QR code dalam format SVG berdasarkan hash.
     */
    protected function generateQrCode(string $hash): string
    {
        $verificationUrl = config('app.url') . '/verifikasi/' . $hash;
        
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(200)
            ->generate($verificationUrl);

        $filename = 'qr_' . $hash . '.svg';
        $path = 'qrcodes/' . $filename;
        
        Storage::disk('public')->put($path, $qrCode);

        return Storage::disk('public')->path($path);
    }

    /**
     * Memastikan logo gampong tersedia di direktori publik.
     *
     * Mengunduh logo dari Wikimedia jika belum ada di storage lokal.
     */
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

    /**
     * Menghasilkan nomor surat resmi berdasarkan format desa.
     *
     * Format: {kode_surat}/{counter}/GAMPONG-UDEUNG/{bulan}/{tahun}.
     * Counter dihitung dari jumlah pengajuan tahun berjalan per kategori.
     */
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
