<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Models\AuditLog;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function loginWarga(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16',
            'no_kk' => 'required|string|size:16',
        ]);

        $penduduk = Penduduk::where('nik', $request->nik)
            ->where('no_kk', $request->no_kk)
            ->first();

        if (! $penduduk || $penduduk->status_mutasi !== 'Tetap') {
            throw ValidationException::withMessages([
                'nik' => ['NIK, No KK, atau status warga tidak valid.'],
            ]);
        }

        $token = $penduduk->createToken('warga-token', ['warga'])->plainTextToken;

        AuditLog::log('warga', $penduduk->nik, 'login', 'penduduk', $penduduk->nik);

        return response()->json([
            'message' => 'Login berhasil',
            'user' => $penduduk,
            'token' => $token,
        ]);
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Administrator::where('username', $request->username)->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
            ]);
        }

        $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;

        AuditLog::log('admin', $admin->id, 'login', 'administrators', $admin->id);

        return response()->json([
            'message' => 'Login berhasil',
            'user' => $admin,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        if ($user instanceof Penduduk) {
            $user->load('keluarga');
        }

        return response()->json([
            'user' => $user,
        ]);
    }

    public function bindTelegram(Request $request)
    {
        $request->validate([
            'telegram_chat_id' => 'required|string|unique:penduduk,telegram_chat_id',
        ]);

        $user = $request->user();

        if (! $user instanceof Penduduk) {
            return response()->json([
                'message' => 'Hanya warga yang dapat bind Telegram',
            ], 403);
        }

        $user->update([
            'telegram_chat_id' => $request->telegram_chat_id,
        ]);

        AuditLog::log('warga', $user->nik, 'bind_telegram', 'penduduk', $user->nik);

        return response()->json([
            'message' => 'Telegram berhasil terhubung',
        ]);
    }
}
