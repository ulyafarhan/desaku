<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;

class VerifikasiController extends Controller
{
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
