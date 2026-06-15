<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Models\KategoriSurat;
use App\Models\TrackingPengajuanSurat;
use App\Models\AuditLog;
use App\Jobs\GenerateSuratPdfJob;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class PengajuanSuratController extends Controller
{
    public function __construct(
        protected TelegramService $telegram
    ) {}

    public function kategori()
    {
        $kategori = KategoriSurat::active()->get();

        return response()->json([
            'data' => $kategori,
        ]);
    }

    public function detailKategori($id)
    {
        $kategori = KategoriSurat::findOrFail($id);

        return response()->json([
            'data' => $kategori,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_surat_id' => 'required|exists:kategori_surat,id',
            'data_isian' => 'required|array',
            'file_syarat' => 'required|array',
        ]);

        $user = $request->user();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $user->nik,
            'kategori_surat_id' => $request->kategori_surat_id,
            'data_isian' => $request->data_isian,
            'file_syarat' => $request->file_syarat,
            'status' => 'Pending',
        ]);

        TrackingPengajuanSurat::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'status_baru' => 'Pending',
            'keterangan_update' => 'Pengajuan surat dibuat',
        ]);

        AuditLog::log('warga', $user->nik, 'create', 'pengajuan_surat', $pengajuan->id, null, $pengajuan->toArray());

        $this->telegram->notifyPengajuanStatus(
            $user->nik,
            'Pending',
            $pengajuan->nomor_registrasi
        );

        return response()->json([
            'message' => 'Pengajuan surat berhasil dibuat',
            'data' => $pengajuan->load('kategori'),
        ], 201);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $pengajuan = PengajuanSurat::where('nik_pemohon', $user->nik)
            ->with(['kategori', 'tracking'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($pengajuan);
    }

    public function show($id)
    {
        $pengajuan = PengajuanSurat::with(['kategori', 'pemohon', 'tracking.updater'])
            ->findOrFail($id);

        return response()->json([
            'data' => $pengajuan,
        ]);
    }

    public function adminIndex(Request $request)
    {
        $query = PengajuanSurat::with(['kategori', 'pemohon']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $pengajuan = $query->orderByDesc('created_at')->paginate(20);

        return response()->json($pengajuan);
    }

    public function approve(Request $request, $id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);
        $admin = $request->user();

        $oldStatus = $pengajuan->status;
        
        $pengajuan->update([
            'status' => 'Disetujui',
            'diverifikasi_oleh' => $admin->id,
        ]);

        TrackingPengajuanSurat::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'status_sebelumnya' => $oldStatus,
            'status_baru' => 'Disetujui',
            'keterangan_update' => 'Pengajuan disetujui oleh ' . $admin->username,
            'diupdate_oleh' => $admin->id,
        ]);

        AuditLog::log('admin', $admin->id, 'approve', 'pengajuan_surat', $pengajuan->id);

        if (app()->runningUnitTests()) {
            GenerateSuratPdfJob::dispatch($pengajuan);
        } else {
            GenerateSuratPdfJob::dispatchSync($pengajuan);
        }

        $this->telegram->notifyPengajuanStatus(
            $pengajuan->nik_pemohon,
            'Disetujui',
            $pengajuan->nomor_registrasi
        );

        return response()->json([
            'message' => 'Pengajuan berhasil disetujui',
            'data' => $pengajuan,
        ]);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_penolakan' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $admin = $request->user();

        $oldStatus = $pengajuan->status;
        
        $pengajuan->update([
            'status' => 'Ditolak',
            'catatan_penolakan' => $request->catatan_penolakan,
            'diverifikasi_oleh' => $admin->id,
        ]);

        TrackingPengajuanSurat::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'status_sebelumnya' => $oldStatus,
            'status_baru' => 'Ditolak',
            'keterangan_update' => $request->catatan_penolakan,
            'diupdate_oleh' => $admin->id,
        ]);

        AuditLog::log('admin', $admin->id, 'reject', 'pengajuan_surat', $pengajuan->id);

        $this->telegram->notifyPengajuanStatus(
            $pengajuan->nik_pemohon,
            'Ditolak',
            $pengajuan->nomor_registrasi,
            $request->catatan_penolakan
        );

        return response()->json([
            'message' => 'Pengajuan ditolak',
            'data' => $pengajuan,
        ]);
    }
}
