<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MutasiPenduduk;
use App\Models\Penduduk;
use App\Models\AuditLog;
use App\Services\TelegramService;
use App\Services\StatistikService;
use Illuminate\Http\Request;

/**
 * @group Mutasi Penduduk
 * 
 * APIs untuk mengelola mutasi penduduk (kelahiran, kematian, kedatangan, kepindahan)
 */
class MutasiPendudukController extends Controller
{
    public function __construct(
        protected TelegramService $telegram,
        protected StatistikService $statistik
    ) {}

    /**
     * Buat Pengajuan Mutasi
     * 
     * Submit pengajuan mutasi penduduk baru.
     * 
     * @authenticated
     * 
     * @bodyParam nik string required NIK penduduk yang mengalami mutasi. Example: 1234567890123456
     * @bodyParam jenis_mutasi string required Jenis mutasi. Example: Kelahiran
     * @bodyParam tanggal_mutasi date required Tanggal terjadinya mutasi. Example: 2026-06-01
     * @bodyParam keterangan string required Keterangan tambahan. Example: Lahir di RSUD
     * @bodyParam dokumen_bukti string required URL dokumen bukti yang sudah diupload. Example: https://storage.com/akta.jpg
     * 
     * @response 201 {
     *   "message": "Pengajuan mutasi berhasil dibuat",
     *   "data": {
     *     "id": 1,
     *     "nik": "1234567890123456",
     *     "jenis_mutasi": "Kelahiran",
     *     "tanggal_mutasi": "2026-06-01",
     *     "keterangan": "Lahir di RSUD",
     *     "dokumen_bukti": "https://storage.com/akta.jpg",
     *     "status_verifikasi": "Pending",
     *     "created_at": "2026-06-01T10:00:00.000000Z"
     *   }
     * }
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|exists:penduduk,nik',
            'jenis_mutasi' => 'required|in:Kelahiran,Kematian,Kedatangan,Kepindahan',
            'tanggal_mutasi' => 'required|date',
            'keterangan' => 'required|string',
            'dokumen_bukti' => 'required|string', // URL file yang sudah diupload
        ]);

        $user = $request->user();

        $mutasi = MutasiPenduduk::create([
            'nik' => $request->nik,
            'jenis_mutasi' => $request->jenis_mutasi,
            'tanggal_mutasi' => $request->tanggal_mutasi,
            'keterangan' => $request->keterangan,
            'dokumen_bukti' => $request->dokumen_bukti,
            'status_verifikasi' => 'Pending',
        ]);

        // Audit log
        AuditLog::log('warga', $user->nik, 'create', 'mutasi_penduduk', $mutasi->id, null, $mutasi->toArray());

        // Notifikasi Telegram
        $this->telegram->notifyMutasiStatus(
            $request->nik,
            $request->jenis_mutasi,
            'Pending'
        );

        return response()->json([
            'message' => 'Pengajuan mutasi berhasil dibuat',
            'data' => $mutasi,
        ], 201);
    }

    /**
     * Daftar Mutasi Saya
     * 
     * Mendapatkan daftar mutasi penduduk milik user yang sedang login.
     * 
     * @authenticated
     * 
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "nik": "1234567890123456",
     *       "jenis_mutasi": "Kelahiran",
     *       "tanggal_mutasi": "2026-06-01",
     *       "status_verifikasi": "Pending",
     *       "created_at": "2026-06-01T10:00:00.000000Z",
     *       "penduduk": {
     *         "nik": "1234567890123456",
     *         "nama_lengkap": "John Doe"
     *       }
     *     }
     *   ],
     *   "links": {},
     *   "meta": {}
     * }
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $mutasi = MutasiPenduduk::where('nik', $user->nik)
            ->with('penduduk')
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($mutasi);
    }

    /**
     * [Admin] Daftar Semua Mutasi
     * 
     * Mendapatkan daftar semua mutasi penduduk (admin only).
     * 
     * @authenticated
     * 
     * @queryParam status_verifikasi string Filter berdasarkan status verifikasi. Example: Pending
     * @queryParam jenis_mutasi string Filter berdasarkan jenis mutasi. Example: Kelahiran
     * 
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "nik": "1234567890123456",
     *       "jenis_mutasi": "Kelahiran",
     *       "tanggal_mutasi": "2026-06-01",
     *       "status_verifikasi": "Pending",
     *       "created_at": "2026-06-01T10:00:00.000000Z",
     *       "penduduk": {
     *         "nama_lengkap": "John Doe"
     *       },
     *       "verifikator": null
     *     }
     *   ],
     *   "links": {},
     *   "meta": {}
     * }
     */
    public function adminIndex(Request $request)
    {
        $query = MutasiPenduduk::with(['penduduk', 'verifikator']);

        if ($request->has('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        if ($request->has('jenis_mutasi')) {
            $query->where('jenis_mutasi', $request->jenis_mutasi);
        }

        $mutasi = $query->orderByDesc('created_at')->paginate(20);

        return response()->json($mutasi);
    }

    /**
     * [Admin] Setujui Mutasi
     * 
     * Menyetujui mutasi penduduk dan update status penduduk (admin only).
     * 
     * @authenticated
     * 
     * @urlParam id integer required ID mutasi penduduk. Example: 1
     * 
     * @response 200 {
     *   "message": "Mutasi berhasil disetujui",
     *   "data": {
     *     "id": 1,
     *     "nik": "1234567890123456",
     *     "jenis_mutasi": "Kelahiran",
     *     "status_verifikasi": "Disetujui",
     *     "diverifikasi_oleh": 1
     *   }
     * }
     */
    public function approve(Request $request, $id)
    {
        $mutasi = MutasiPenduduk::findOrFail($id);
        $admin = $request->user();

        $mutasi->update([
            'status_verifikasi' => 'Disetujui',
            'diverifikasi_oleh' => $admin->id,
        ]);

        // Update status penduduk berdasarkan jenis mutasi
        $penduduk = Penduduk::find($mutasi->nik);
        
        if (in_array($mutasi->jenis_mutasi, ['Kematian', 'Kepindahan'])) {
            $penduduk->update([
                'status_mutasi' => $mutasi->jenis_mutasi === 'Kematian' ? 'Meninggal' : 'Pindah',
            ]);
        }

        // Audit log
        AuditLog::log('admin', $admin->id, 'approve', 'mutasi_penduduk', $mutasi->id);

        // Clear cache statistik
        $this->statistik->clearCache();

        // Notifikasi Telegram
        $this->telegram->notifyMutasiStatus(
            $mutasi->nik,
            $mutasi->jenis_mutasi,
            'Disetujui'
        );

        return response()->json([
            'message' => 'Mutasi berhasil disetujui',
            'data' => $mutasi,
        ]);
    }

    /**
     * [Admin] Tolak Mutasi
     * 
     * Menolak mutasi penduduk (admin only).
     * 
     * @authenticated
     * 
     * @urlParam id integer required ID mutasi penduduk. Example: 1
     * 
     * @response 200 {
     *   "message": "Mutasi ditolak",
     *   "data": {
     *     "id": 1,
     *     "nik": "1234567890123456",
     *     "jenis_mutasi": "Kelahiran",
     *     "status_verifikasi": "Ditolak",
     *     "diverifikasi_oleh": 1
     *   }
     * }
     */
    public function reject(Request $request, $id)
    {
        $mutasi = MutasiPenduduk::findOrFail($id);
        $admin = $request->user();

        $mutasi->update([
            'status_verifikasi' => 'Ditolak',
            'diverifikasi_oleh' => $admin->id,
        ]);

        // Audit log
        AuditLog::log('admin', $admin->id, 'reject', 'mutasi_penduduk', $mutasi->id);

        // Notifikasi Telegram
        $this->telegram->notifyMutasiStatus(
            $mutasi->nik,
            $mutasi->jenis_mutasi,
            'Ditolak'
        );

        return response()->json([
            'message' => 'Mutasi ditolak',
            'data' => $mutasi,
        ]);
    }
}
