<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller untuk mengelola data anggota keluarga (Kartu Keluarga).
 */
class CitizenFamilyController extends Controller
{
    /**
     * Menampilkan daftar anggota keluarga yang terdaftar dalam KK yang sama.
     */
    public function index(Request $request): Response
    {
        $warga = $request->user('penduduk');
        $keluarga = $warga->keluarga;

        $isKepalaKeluarga = $keluarga && $keluarga->kepala_keluarga_nik === $warga->nik;

        $anggota = $keluarga
            ? Penduduk::where('no_kk', $keluarga->no_kk)
                ->aktif()
                ->orderByRaw("CASE status_keluarga WHEN 'Kepala Keluarga' THEN 1 WHEN 'Istri' THEN 2 WHEN 'Anak' THEN 3 ELSE 4 END")
                ->get()
                ->map(fn ($p) => [
                    'nik' => $p->nik,
                    'nama_lengkap' => $p->nama_lengkap,
                    'jenis_kelamin' => $p->jenis_kelamin,
                    'tempat_lahir' => $p->tempat_lahir,
                    'tanggal_lahir' => $p->tanggal_lahir?->format('Y-m-d'),
                    'umur' => $p->umur,
                    'agama' => $p->agama,
                    'pendidikan' => $p->pendidikan,
                    'pekerjaan' => $p->pekerjaan,
                    'status_perkawinan' => $p->status_perkawinan,
                    'status_keluarga' => $p->status_keluarga,
                    'status_mutasi' => $p->status_mutasi,
                ])
            : collect();

        return Inertia::render('Citizen/Family', [
            'keluarga' => $keluarga,
            'anggota' => $anggota,
            'isKepalaKeluarga' => $isKepalaKeluarga,
        ]);
    }

    /**
     * Memperbarui informasi profil anggota keluarga tertentu (Hanya boleh diakses Kepala Keluarga).
     */
    public function update(Request $request, string $nik): RedirectResponse
    {
        $warga = $request->user('penduduk');
        $keluarga = $warga->keluarga;

        abort_unless(
            $keluarga && $keluarga->kepala_keluarga_nik === $warga->nik,
            403,
            'Hanya Kepala Keluarga yang dapat mengedit data anggota.'
        );

        $anggota = Penduduk::where('nik', $nik)
            ->where('no_kk', $keluarga->no_kk)
            ->firstOrFail();

        $validated = $request->validate([
            'pendidikan' => ['nullable', 'string', 'max:50'],
            'pekerjaan' => ['nullable', 'string', 'max:50'],
            'status_perkawinan' => ['nullable', 'string', 'max:20'],
        ]);

        $anggota->update($validated);

        return back()->with('success', 'Data anggota keluarga berhasil diperbarui.');
    }
}
