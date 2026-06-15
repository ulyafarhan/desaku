<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MutasiPenduduk;
use App\Models\Penduduk;
use App\Models\AuditLog;
use App\Services\TelegramService;
use App\Services\StatistikService;
use Illuminate\Http\Request;

class MutasiPendudukController extends Controller
{
    public function __construct(
        protected TelegramService $telegram,
        protected StatistikService $statistik
    ) {}

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|exists:penduduk,nik',
            'jenis_mutasi' => 'required|in:Kelahiran,Kematian,Kedatangan,Kepindahan',
            'tanggal_mutasi' => 'required|date',
            'keterangan' => 'required|string',
            'dokumen_bukti' => 'required|string',
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

        AuditLog::log('warga', $user->nik, 'create', 'mutasi_penduduk', $mutasi->id, null, $mutasi->toArray());

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

    public function index(Request $request)
    {
        $user = $request->user();

        $mutasi = MutasiPenduduk::where('nik', '=', $user->nik)
            ->with('penduduk')
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($mutasi);
    }

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

    public function approve(Request $request, $id)
    {
        $mutasi = MutasiPenduduk::findOrFail($id);
        $admin = $request->user();

        $mutasi->update([
            'status_verifikasi' => 'Disetujui',
            'diverifikasi_oleh' => $admin->id,
        ]);

        $penduduk = Penduduk::find($mutasi->nik);
        
        if (in_array($mutasi->jenis_mutasi, ['Kematian', 'Kepindahan'])) {
            $penduduk->update([
                'status_mutasi' => $mutasi->jenis_mutasi === 'Kematian' ? 'Meninggal' : 'Pindah',
            ]);
        }

        AuditLog::log('admin', $admin->id, 'approve', 'mutasi_penduduk', $mutasi->id);

        $this->statistik->clearCache();

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

    public function reject(Request $request, $id)
    {
        $mutasi = MutasiPenduduk::findOrFail($id);
        $admin = $request->user();

        $mutasi->update([
            'status_verifikasi' => 'Ditolak',
            'diverifikasi_oleh' => $admin->id,
        ]);

        AuditLog::log('admin', $admin->id, 'reject', 'mutasi_penduduk', $mutasi->id);

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
