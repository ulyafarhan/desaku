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

/**
 * @group Pengajuan Surat
 * 
 * APIs untuk mengelola pengajuan surat warga
 */
class PengajuanSuratController extends Controller
{
    public function __construct(
        protected TelegramService $telegram
    ) {}

    /**
     * Daftar Kategori Surat
     * 
     * Mendapatkan daftar kategori surat yang tersedia dan aktif.
     * 
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "nama_surat": "Surat Keterangan Domisili",
     *       "kode_surat": "SKD",
     *       "deskripsi": "Surat keterangan tempat tinggal",
     *       "template_path": "pdf.surat.domisili",
     *       "persyaratan": ["KTP", "KK"],
     *       "field_isian": ["keperluan", "alamat_lengkap"],
     *       "is_active": true
     *     }
     *   ]
     * }
     */
    public function kategori()
    {
        $kategori = KategoriSurat::active()->get();

        return response()->json([
            'data' => $kategori,
        ]);
    }

    /**
     * Detail Kategori Surat
     * 
     * Mendapatkan detail kategori surat tertentu.
     * 
     * @urlParam id integer required ID kategori surat. Example: 1
     * 
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "nama_surat": "Surat Keterangan Domisili",
     *     "kode_surat": "SKD",
     *     "deskripsi": "Surat keterangan tempat tinggal",
     *     "template_path": "pdf.surat.domisili",
     *     "persyaratan": ["KTP", "KK"],
     *     "field_isian": ["keperluan", "alamat_lengkap"],
     *     "is_active": true
     *   }
     * }
     */
    public function detailKategori($id)
    {
        $kategori = KategoriSurat::findOrFail($id);

        return response()->json([
            'data' => $kategori,
        ]);
    }

    /**
     * Buat Pengajuan Surat
     * 
     * Submit pengajuan surat baru oleh warga.
     * 
     * @authenticated
     * 
     * @bodyParam kategori_surat_id integer required ID kategori surat. Example: 1
     * @bodyParam data_isian object required Data isian sesuai field_isian kategori. Example: {"keperluan": "Melamar pekerjaan", "alamat_lengkap": "Jl. Merdeka No. 123"}
     * @bodyParam file_syarat object required File persyaratan yang sudah diupload. Example: {"ktp": "https://storage.com/ktp.jpg", "kk": "https://storage.com/kk.jpg"}
     * 
     * @response 201 {
     *   "message": "Pengajuan surat berhasil dibuat",
     *   "data": {
     *     "id": 1,
     *     "nomor_registrasi": "REG/2026/06/00001",
     *     "nik_pemohon": "1234567890123456",
     *     "kategori_surat_id": 1,
     *     "data_isian": {"keperluan": "Melamar pekerjaan"},
     *     "file_syarat": {"ktp": "https://storage.com/ktp.jpg"},
     *     "status": "Pending",
     *     "created_at": "2026-06-01T10:00:00.000000Z",
     *     "kategori": {
     *       "id": 1,
     *       "nama_surat": "Surat Keterangan Domisili"
     *     }
     *   }
     * }
     */
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

        // Create tracking
        TrackingPengajuanSurat::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'status_baru' => 'Pending',
            'keterangan_update' => 'Pengajuan surat dibuat',
        ]);

        // Audit log
        AuditLog::log('warga', $user->nik, 'create', 'pengajuan_surat', $pengajuan->id, null, $pengajuan->toArray());

        // Notifikasi Telegram
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

    /**
     * Daftar Pengajuan Surat Saya
     * 
     * Mendapatkan daftar pengajuan surat milik user yang sedang login.
     * 
     * @authenticated
     * 
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "nomor_registrasi": "REG/2026/06/00001",
     *       "nik_pemohon": "1234567890123456",
     *       "status": "Pending",
     *       "created_at": "2026-06-01T10:00:00.000000Z",
     *       "kategori": {
     *         "nama_surat": "Surat Keterangan Domisili"
     *       },
     *       "tracking": [
     *         {
     *           "status_baru": "Pending",
     *           "keterangan_update": "Pengajuan surat dibuat",
     *           "created_at": "2026-06-01T10:00:00.000000Z"
     *         }
     *       ]
     *     }
     *   ],
     *   "links": {},
     *   "meta": {}
     * }
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $pengajuan = PengajuanSurat::where('nik_pemohon', $user->nik)
            ->with(['kategori', 'tracking'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($pengajuan);
    }

    /**
     * Detail Pengajuan Surat
     * 
     * Mendapatkan detail pengajuan surat tertentu.
     * 
     * @authenticated
     * 
     * @urlParam id integer required ID pengajuan surat. Example: 1
     * 
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "nomor_registrasi": "REG/2026/06/00001",
     *     "nik_pemohon": "1234567890123456",
     *     "kategori_surat_id": 1,
     *     "data_isian": {"keperluan": "Melamar pekerjaan"},
     *     "file_syarat": {"ktp": "https://storage.com/ktp.jpg"},
     *     "status": "Disetujui",
     *     "file_surat": "https://storage.com/surat.pdf",
     *     "qr_hash": "abc123def456",
     *     "created_at": "2026-06-01T10:00:00.000000Z",
     *     "kategori": {
     *       "nama_surat": "Surat Keterangan Domisili"
     *     },
     *     "pemohon": {
     *       "nik": "1234567890123456",
     *       "nama_lengkap": "John Doe"
     *     },
     *     "tracking": []
     *   }
     * }
     */
    public function show($id)
    {
        $pengajuan = PengajuanSurat::with(['kategori', 'pemohon', 'tracking.updater'])
            ->findOrFail($id);

        return response()->json([
            'data' => $pengajuan,
        ]);
    }

    /**
     * [Admin] Daftar Semua Pengajuan
     * 
     * Mendapatkan daftar semua pengajuan surat (admin only).
     * 
     * @authenticated
     * 
     * @queryParam status string Filter berdasarkan status. Example: Pending
     * 
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "nomor_registrasi": "REG/2026/06/00001",
     *       "nik_pemohon": "1234567890123456",
     *       "status": "Pending",
     *       "created_at": "2026-06-01T10:00:00.000000Z",
     *       "kategori": {
     *         "nama_surat": "Surat Keterangan Domisili"
     *       },
     *       "pemohon": {
     *         "nama_lengkap": "John Doe"
     *       }
     *     }
     *   ],
     *   "links": {},
     *   "meta": {}
     * }
     */
    public function adminIndex(Request $request)
    {
        $query = PengajuanSurat::with(['kategori', 'pemohon']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $pengajuan = $query->orderByDesc('created_at')->paginate(20);

        return response()->json($pengajuan);
    }

    /**
     * [Admin] Setujui Pengajuan
     * 
     * Menyetujui pengajuan surat dan memulai proses generate PDF (admin only).
     * 
     * @authenticated
     * 
     * @urlParam id integer required ID pengajuan surat. Example: 1
     * 
     * @response 200 {
     *   "message": "Pengajuan berhasil disetujui",
     *   "data": {
     *     "id": 1,
     *     "nomor_registrasi": "REG/2026/06/00001",
     *     "status": "Disetujui",
     *     "diverifikasi_oleh": 1
     *   }
     * }
     */
    public function approve(Request $request, $id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);
        $admin = $request->user();

        $oldStatus = $pengajuan->status;
        
        $pengajuan->update([
            'status' => 'Disetujui',
            'diverifikasi_oleh' => $admin->id,
        ]);

        // Create tracking
        TrackingPengajuanSurat::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'status_sebelumnya' => $oldStatus,
            'status_baru' => 'Disetujui',
            'keterangan_update' => 'Pengajuan disetujui oleh ' . $admin->username,
            'diupdate_oleh' => $admin->id,
        ]);

        // Audit log
        AuditLog::log('admin', $admin->id, 'approve', 'pengajuan_surat', $pengajuan->id);

        // Dispatch job untuk generate PDF
        GenerateSuratPdfJob::dispatch($pengajuan);

        // Notifikasi Telegram
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

    /**
     * [Admin] Tolak Pengajuan
     * 
     * Menolak pengajuan surat dengan catatan penolakan (admin only).
     * 
     * @authenticated
     * 
     * @urlParam id integer required ID pengajuan surat. Example: 1
     * @bodyParam catatan_penolakan string required Alasan penolakan. Example: Dokumen persyaratan tidak lengkap
     * 
     * @response 200 {
     *   "message": "Pengajuan ditolak",
     *   "data": {
     *     "id": 1,
     *     "nomor_registrasi": "REG/2026/06/00001",
     *     "status": "Ditolak",
     *     "catatan_penolakan": "Dokumen persyaratan tidak lengkap",
     *     "diverifikasi_oleh": 1
     *   }
     * }
     */
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

        // Create tracking
        TrackingPengajuanSurat::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'status_sebelumnya' => $oldStatus,
            'status_baru' => 'Ditolak',
            'keterangan_update' => $request->catatan_penolakan,
            'diupdate_oleh' => $admin->id,
        ]);

        // Audit log
        AuditLog::log('admin', $admin->id, 'reject', 'pengajuan_surat', $pengajuan->id);

        // Notifikasi Telegram
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
