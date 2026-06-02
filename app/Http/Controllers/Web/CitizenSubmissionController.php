<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\KategoriSurat;
use App\Models\PengajuanSurat;
use App\Models\TrackingPengajuanSurat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CitizenSubmissionController extends Controller
{
    public function create(KategoriSurat $kategori): Response
    {
        abort_unless($kategori->is_active, 404);

        return Inertia::render('Citizen/Submission/Create', [
            'kategori' => $kategori,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_surat_id' => ['required', 'exists:kategori_surat,id'],
            'data_isian' => ['required', 'array'],
            'file_syarat' => ['required', 'array'],
        ]);

        $warga = $request->user('penduduk');
        $kategori = KategoriSurat::query()->active()->findOrFail($validated['kategori_surat_id']);

        foreach ($kategori->schema_isian ?? [] as $field) {
            if (($field['required'] ?? false) && blank(data_get($validated, 'data_isian.' . $field['field']))) {
                return back()->withErrors(['data_isian.' . $field['field'] => 'Kolom ini wajib diisi.'])->withInput();
            }
        }

        foreach ($kategori->syarat_dokumen ?? [] as $dokumen) {
            $key = str($dokumen)->lower()->slug('_')->toString();

            if (blank(data_get($validated, 'file_syarat.' . $key))) {
                return back()->withErrors(['file_syarat.' . $key => 'Dokumen ini wajib diisi.'])->withInput();
            }
        }

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $warga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => $validated['data_isian'],
            'file_syarat' => $validated['file_syarat'],
            'status' => 'Pending',
        ]);

        TrackingPengajuanSurat::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'status_baru' => 'Pending',
            'keterangan_update' => 'Pengajuan surat dibuat',
        ]);

        return redirect()
            ->route('warga.pengajuan.show', $pengajuan)
            ->with('success', 'Pengajuan surat berhasil dibuat.');
    }

    public function show(Request $request, PengajuanSurat $pengajuan): Response
    {
        abort_unless($pengajuan->nik_pemohon === $request->user('penduduk')->nik, 403);

        return Inertia::render('Citizen/Submission/Show', [
            'pengajuan' => $pengajuan->load(['kategori', 'pemohon.keluarga', 'tracking.updater']),
        ]);
    }
}
