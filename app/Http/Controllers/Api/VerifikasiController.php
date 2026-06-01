<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;

/**
 * @group Verifikasi
 * 
 * API untuk verifikasi keaslian dokumen surat melalui QR Code TTE (Tanda Tangan Elektronik)
 */
class VerifikasiController extends Controller
{
    /**
     * Verifikasi QR Code TTE
     * 
     * Memverifikasi keaslian dokumen surat berdasarkan hash QR Code.
     * Hash menggunakan SHA-256 untuk keamanan.
     * 
     * @urlParam hash string required Hash SHA-256 dari QR Code. Example: abc123def456789
     * 
     * @response 200 {
     *   "valid": true,
     *   "message": "Dokumen valid",
     *   "data": {
     *     "nomor_registrasi": "REG/2026/06/00001",
     *     "jenis_surat": "Surat Keterangan Domisili",
     *     "nama_pemohon": "John Doe",
     *     "nik_pemohon": "1234567890123456",
     *     "tanggal_terbit": "01-06-2026",
     *     "diverifikasi_oleh": "operator"
     *   }
     * }
     * 
     * @response 404 {
     *   "valid": false,
     *   "message": "Dokumen tidak ditemukan atau tidak valid"
     * }
     * 
     * @response 200 {
     *   "valid": false,
     *   "message": "Dokumen belum selesai diproses"
     * }
     */
    public function verify($hash)
    {
        $pengajuan = PengajuanSurat::where('qr_hash', $hash)
            ->with(['kategori', 'pemohon', 'verifikator'])
            ->first();

        if (!$pengajuan) {
            return response()->json([
                'valid' => false,
                'message' => 'Dokumen tidak ditemukan atau tidak valid',
            ], 404);
        }

        if ($pengajuan->status !== 'Selesai') {
            return response()->json([
                'valid' => false,
                'message' => 'Dokumen belum selesai diproses',
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Dokumen valid',
            'data' => [
                'nomor_registrasi' => $pengajuan->nomor_registrasi,
                'jenis_surat' => $pengajuan->kategori->nama_surat,
                'nama_pemohon' => $pengajuan->pemohon->nama_lengkap,
                'nik_pemohon' => $pengajuan->pemohon->nik,
                'tanggal_terbit' => $pengajuan->updated_at->format('d-m-Y'),
                'diverifikasi_oleh' => $pengajuan->verifikator?->username,
            ],
        ]);
    }
}
