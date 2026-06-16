<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller untuk menangani autentikasi warga menggunakan NIK dan Nomor KK.
 */
class CitizenAuthController extends Controller
{
    /**
     * Menampilkan halaman formulir login warga.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Memproses permintaan autentikasi masuk (login) warga.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'nik' => ['required', 'string', 'size:16'],
            'no_kk' => ['required', 'string', 'size:16'],
        ]);

        $penduduk = Penduduk::query()
            ->where('nik', $credentials['nik'])
            ->where('no_kk', $credentials['no_kk'])
            ->first();

        if (! $penduduk || $penduduk->status_mutasi !== 'Tetap') {
            throw ValidationException::withMessages([
                'nik' => 'NIK, No KK, atau status warga tidak valid.',
            ]);
        }

        Auth::guard('penduduk')->login($penduduk);
        $request->session()->regenerate();

        return redirect()->intended(route('warga.dashboard'));
    }

    /**
     * Memproses permintaan keluar (logout) warga.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('penduduk')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda berhasil keluar.');
    }
}
