<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CitizenProfileController extends Controller
{
    public function show(Request $request): Response
    {
        $warga = $request->user('penduduk');
        $warga->load('keluarga');

        $requiredFields = ['nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pendidikan', 'pekerjaan', 'status_perkawinan'];
        $filled = collect($requiredFields)->filter(fn ($field) => !blank($warga->{$field}))->count();
        $completeness = round(($filled / count($requiredFields)) * 100);

        return Inertia::render('Citizen/Profile', [
            'warga' => $warga->makeVisible('telegram_chat_id'),
            'completeness' => $completeness,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pendidikan' => ['nullable', 'string', 'max:50'],
            'pekerjaan' => ['nullable', 'string', 'max:50'],
            'status_perkawinan' => ['nullable', 'string', 'max:20'],
            'telegram_chat_id' => ['nullable', 'string', 'max:50'],
            'foto_profil' => ['nullable', 'image', 'max:2048'],
            'foto_ktp' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'foto_kk' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $warga = $request->user('penduduk');
        $dataToUpdate = collect($validated)->except(['foto_profil', 'foto_ktp', 'foto_kk'])->toArray();

        if ($request->hasFile('foto_profil')) {
            $dataToUpdate['foto_profil'] = $request->file('foto_profil')->store('warga/profiles', 'public');
        }
        if ($request->hasFile('foto_ktp')) {
            $dataToUpdate['foto_ktp'] = $request->file('foto_ktp')->store('warga/documents', 'public');
        }
        if ($request->hasFile('foto_kk')) {
            $dataToUpdate['foto_kk'] = $request->file('foto_kk')->store('warga/documents', 'public');
        }

        $warga->update($dataToUpdate);

        return back()->with('success', 'Biodata berhasil diperbarui.');
    }
}
