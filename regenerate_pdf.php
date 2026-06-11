<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PengajuanSurat;
use App\Services\PdfGeneratorService;

$pengajuan = PengajuanSurat::find(56);
if ($pengajuan) {
    echo "Found Pengajuan ID: 56\n";
    $service = new PdfGeneratorService();
    $pdfUrl = $service->generateSuratPdf($pengajuan);
    
    $pengajuan->refresh();
    echo "Regenerated PDF URL: " . $pengajuan->file_pdf_url . "\n";
} else {
    echo "Error: Pengajuan ID 56 not found.\n";
}
