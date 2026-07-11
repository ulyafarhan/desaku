<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Models\AuditLog;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Controller untuk menangani API Autentikasi (warga & admin) berbasis Token.
 *
 * @group Autentikasi
 */
class AuthController extends Controller
{
    /**
     * Memproses login warga menggunakan NIK dan Nomor Kartu Keluarga via API.
     *
     * @unauthenticated
     *
     * @bodyParameter nik string required NIK warga (16 digit). Contoh: 1118060512900001.
     * @bodyParameter no_kk string required Nomor Kartu Keluarga (16 digit). Contoh: 1118060512900002.
     *
     * @responseField message string Pesan hasil operasi.
     * @responseField user object Data profil warga yang berhasil login.
     * @responseField token string Token autentikasi Bearer.
     *
     * @response {
     *   "message": "Login berhasil",
     *   "user": {
     *     "nik": "1118060512900001",
     *     "nama_lengkap": "Ahmad Fauzi",
     *     "no_kk": "1118060512900002",
     *     "status_mutasi": "Tetap"
     *   },
     *   "token": "1|abcdefghijklmnopqrstuvwxyz123456"
     * }
     *
     * @throws \Illuminate\Validation\ValidationException Jika validasi gagal atau kredensial tidak valid.
     */
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

    /**
     * Memproses login administrator menggunakan Username dan Password via API.
     *
     * @unauthenticated
     *
     * @bodyParameter username string required Username administrator. Contoh: admin_desa.
     * @bodyParameter password string required Password administrator. Contoh: secretpassword.
     *
     * @responseField message string Pesan hasil operasi.
     * @responseField user object Data profil admin yang berhasil login.
     * @responseField token string Token autentikasi Bearer.
     *
     * @response {
     *   "message": "Login berhasil",
     *   "user": {
     *     "id": 1,
     *     "username": "admin_desa",
     *     "nama_lengkap": "Administrator Desa"
     *   },
     *   "token": "2|abcdefghijklmnopqrstuvwxyz123456"
     * }
     *
     * @throws \Illuminate\Validation\ValidationException Jika validasi gagal atau kredensial tidak valid.
     */
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

    /**
     * Memproses keluar log (logout) dengan menghapus token akses saat ini.
     *
     * @authenticated
     *
     * @responseField message string Pesan hasil operasi.
     *
     * @response {
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
     * Mengambil detail informasi profil pengguna yang sedang login.
     *
     * @authenticated
     *
     * @responseField user object Data profil pengguna yang sedang login.
     *
     * @response {
     *   "user": {
     *     "nik": "1118060512900001",
     *     "nama_lengkap": "Ahmad Fauzi",
     *     "no_kk": "1118060512900002",
     *     "status_mutasi": "Tetap",
     *     "keluarga": {
     *       "no_kk": "1118060512900002",
     *       "nama_kepala_keluarga": "Ahmad Fauzi"
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
     * Menghubungkan ID chat Telegram dengan akun warga.
     *
     * @authenticated
     *
     * @bodyParameter telegram_chat_id string required ID chat Telegram unik. Contoh: 123456789.
     *
     * @responseField message string Pesan hasil operasi.
     *
     * @response {
     *   "message": "Telegram berhasil terhubung"
     * }
     *
     * @response 403 {
     *   "message": "Hanya warga yang dapat bind Telegram"
     * }
     *
     * @throws \Illuminate\Validation\ValidationException Jika chat ID sudah terpakai.
     */
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
