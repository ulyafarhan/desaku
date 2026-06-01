<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Models\Penduduk;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @group Authentication
 * 
 * APIs untuk autentikasi warga dan admin
 */
class AuthController extends Controller
{
    /**
     * Login Warga (NIK)
     * 
     * Login menggunakan NIK untuk warga gampong.
     * 
     * @bodyParam nik string required NIK warga (16 digit). Example: 1234567890123456
     * 
     * @response 200 {
     *   "message": "Login berhasil",
     *   "user": {
     *     "nik": "1234567890123456",
     *     "nama_lengkap": "John Doe",
     *     "tempat_lahir": "Jakarta",
     *     "tanggal_lahir": "1990-01-01",
     *     "jenis_kelamin": "L",
     *     "agama": "Islam",
     *     "pendidikan": "S1",
     *     "pekerjaan": "Programmer",
     *     "status_perkawinan": "Belum Kawin",
     *     "status_keluarga": "Anak",
     *     "status_mutasi": "Tetap"
     *   },
     *   "token": "1|abcdefghijklmnopqrstuvwxyz"
     * }
     * 
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "nik": ["NIK tidak ditemukan atau tidak aktif."]
     *   }
     * }
     */
    public function loginWarga(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16',
        ]);

        $penduduk = Penduduk::find($request->nik);

        if (!$penduduk || $penduduk->status_mutasi !== 'Tetap') {
            throw ValidationException::withMessages([
                'nik' => ['NIK tidak ditemukan atau tidak aktif.'],
            ]);
        }

        $token = $penduduk->createToken('warga-token')->plainTextToken;

        AuditLog::log('warga', $penduduk->nik, 'login', 'penduduk', $penduduk->nik);

        return response()->json([
            'message' => 'Login berhasil',
            'user' => $penduduk,
            'token' => $token,
        ]);
    }

    /**
     * Login Admin
     * 
     * Login menggunakan username dan password untuk admin (Keuchik, Sekdes, Operator).
     * 
     * @bodyParam username string required Username admin. Example: operator
     * @bodyParam password string required Password admin. Example: password123
     * 
     * @response 200 {
     *   "message": "Login berhasil",
     *   "user": {
     *     "id": 1,
     *     "username": "operator",
     *     "role": "operator",
     *     "created_at": "2024-01-01T00:00:00.000000Z"
     *   },
     *   "token": "2|abcdefghijklmnopqrstuvwxyz"
     * }
     * 
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "username": ["Username atau password salah."]
     *   }
     * }
     */
    public function loginAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Administrator::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
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

    /**
     * Logout
     * 
     * Logout dan hapus token akses saat ini.
     * 
     * @authenticated
     * 
     * @response 200 {
     *   "message": "Logout berhasil"
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }

    /**
     * Get Profile
     * 
     * Mendapatkan profil user yang sedang login (warga atau admin).
     * 
     * @authenticated
     * 
     * @response 200 {
     *   "user": {
     *     "nik": "1234567890123456",
     *     "nama_lengkap": "John Doe",
     *     "tempat_lahir": "Jakarta",
     *     "tanggal_lahir": "1990-01-01",
     *     "jenis_kelamin": "L",
     *     "agama": "Islam",
     *     "pendidikan": "S1",
     *     "pekerjaan": "Programmer",
     *     "status_perkawinan": "Belum Kawin",
     *     "status_keluarga": "Anak",
     *     "status_mutasi": "Tetap",
     *     "keluarga": {
     *       "no_kk": "1234567890123456",
     *       "alamat": "Jl. Merdeka No. 123"
     *     }
     *   }
     * }
     */
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

    /**
     * Bind Telegram
     * 
     * Menghubungkan akun warga dengan Telegram Chat ID untuk notifikasi.
     * 
     * @authenticated
     * 
     * @bodyParam telegram_chat_id string required Telegram Chat ID dari bot. Example: 123456789
     * 
     * @response 200 {
     *   "message": "Telegram berhasil terhubung"
     * }
     * 
     * @response 403 {
     *   "message": "Hanya warga yang dapat bind Telegram"
     * }
     * 
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "telegram_chat_id": ["The telegram chat id has already been taken."]
     *   }
     * }
     */
    public function bindTelegram(Request $request)
    {
        $request->validate([
            'telegram_chat_id' => 'required|string|unique:penduduk,telegram_chat_id',
        ]);

        $user = $request->user();

        if (!$user instanceof Penduduk) {
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
