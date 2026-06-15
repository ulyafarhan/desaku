<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrateStorageToCloudCommand extends Command
{
    protected $signature = 'storage:migrate-to-cloud';
    protected $description = 'Migrate all local storage uploaded files to cloud storage (S3/R2)';

    public function handle(): int
    {
        $this->info('Starting file migration to cloud storage...');
        Log::info('Storage migration to cloud started.');

        $count = 0;
        $failed = 0;
        $skipped = 0;

        try {
            $s3Disk = Storage::disk('s3');

            $this->info('Processing Penduduk uploads...');
            $penduduks = DB::table('penduduk')
                ->whereNotNull('foto_profil')
                ->orWhereNotNull('foto_ktp')
                ->orWhereNotNull('foto_kk')
                ->get();

            foreach ($penduduks as $p) {
                foreach (['foto_profil', 'foto_ktp', 'foto_kk'] as $field) {
                    $path = $p->{$field};
                    if ($path && !str_starts_with($path, 'http://') && !str_starts_with($path, 'https://')) {
                        $this->migrateFile($path, $count, $skipped, $failed);
                    }
                }
            }

            $this->info('Processing Pengajuan Surat uploads...');
            $pengajuans = DB::table('pengajuan_surat')->get();
            foreach ($pengajuans as $ps) {
                if ($ps->file_syarat) {
                    $files = json_decode($ps->file_syarat, true);
                    if (is_array($files)) {
                        foreach ($files as $key => $path) {
                            if ($path && !str_starts_with($path, 'http://') && !str_starts_with($path, 'https://')) {
                                $this->migrateFile($path, $count, $skipped, $failed);
                            }
                        }
                    }
                }
                $pdfPath = $ps->file_pdf_url;
                if ($pdfPath && !str_starts_with($pdfPath, 'http://') && !str_starts_with($pdfPath, 'https://')) {
                    $this->migrateFile($pdfPath, $count, $skipped, $failed);
                }
            }

            $this->info('Processing Mutasi Penduduk uploads...');
            $mutasis = DB::table('mutasi_penduduk')
                ->whereNotNull('dokumen_bukti')
                ->get();
            foreach ($mutasis as $m) {
                $path = $m->dokumen_bukti;
                if ($path && !str_starts_with($path, 'http://') && !str_starts_with($path, 'https://')) {
                    $this->migrateFile($path, $count, $skipped, $failed);
                }
            }

            $this->info('Processing Informasi Publik uploads...');
            $infos = DB::table('informasi_publik')
                ->whereNotNull('cover_image')
                ->get();
            foreach ($infos as $info) {
                $path = $info->cover_image;
                if ($path && !str_starts_with($path, 'http://') && !str_starts_with($path, 'https://')) {
                    $this->migrateFile($path, $count, $skipped, $failed);
                }
            }

            $this->info("Migration completed. Successfully migrated: $count, Skipped: $skipped, Failed: $failed");
            Log::info("Storage migration to cloud completed. Migrated: $count, Skipped: $skipped, Failed: $failed");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Migration failed: ' . $e->getMessage());
            Log::error('Storage migration to cloud failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function migrateFile(string $path, int &$count, int &$skipped, int &$failed): void
    {
        try {
            if (!Storage::disk('public')->exists($path)) {
                $skipped++;
                return;
            }

            if (Storage::disk('s3')->exists($path)) {
                $skipped++;
                return;
            }

            $fileContent = Storage::disk('public')->get($path);
            $mimeType = Storage::disk('public')->mimeType($path);
            
            Storage::disk('s3')->put($path, $fileContent, [
                'visibility' => 'public',
                'ContentType' => $mimeType
            ]);

            $count++;
            $this->line("Migrated: $path");
        } catch (\Exception $e) {
            $failed++;
            $this->error("Failed to migrate $path: " . $e->getMessage());
        }
    }
}
