<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InformasiPublik;
use App\Models\AuditLog;
use Illuminate\Http\Request;

/**
 * @group Informasi Publik
 * 
 * APIs untuk mengelola informasi publik gampong (berita, pengumuman, agenda)
 */
class InformasiPublikController extends Controller
{
    /**
     * Daftar Informasi Publik
     * 
     * Mendapatkan daftar informasi publik yang sudah dipublikasikan.
     * 
     * @queryParam kategori string Filter berdasarkan kategori. Example: Berita
     * 
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "judul": "Musyawarah Gampong 2026",
     *       "slug": "musyawarah-gampong-2026",
     *       "konten": "Akan dilaksanakan musyawarah...",
     *       "kategori": "Pengumuman",
     *       "cover_image": "https://storage.com/cover.jpg",
     *       "is_published": true,
     *       "created_at": "2026-06-01T10:00:00.000000Z",
     *       "author": {
     *         "id": 1,
     *         "username": "operator"
     *       }
     *     }
     *   ],
     *   "links": {},
     *   "meta": {}
     * }
     */
    public function index(Request $request)
    {
        $query = InformasiPublik::published()->with('author');

        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $informasi = $query->orderByDesc('created_at')->paginate(10);

        return response()->json($informasi);
    }

    /**
     * Detail Informasi Publik
     * 
     * Mendapatkan detail informasi publik berdasarkan slug.
     * 
     * @urlParam slug string required Slug informasi. Example: musyawarah-gampong-2026
     * 
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "judul": "Musyawarah Gampong 2026",
     *     "slug": "musyawarah-gampong-2026",
     *     "konten": "Akan dilaksanakan musyawarah...",
     *     "kategori": "Pengumuman",
     *     "cover_image": "https://storage.com/cover.jpg",
     *     "is_published": true,
     *     "created_at": "2026-06-01T10:00:00.000000Z",
     *     "author": {
     *       "id": 1,
     *       "username": "operator"
     *     }
     *   }
     * }
     */
    public function show($slug)
    {
        $informasi = InformasiPublik::where('slug', $slug)
            ->published()
            ->with('author')
            ->firstOrFail();

        return response()->json([
            'data' => $informasi,
        ]);
    }

    /**
     * [Admin] Daftar Semua Informasi
     * 
     * Mendapatkan daftar semua informasi termasuk draft (admin only).
     * 
     * @authenticated
     * 
     * @queryParam is_published boolean Filter berdasarkan status publikasi. Example: true
     * 
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "judul": "Musyawarah Gampong 2026",
     *       "slug": "musyawarah-gampong-2026",
     *       "kategori": "Pengumuman",
     *       "is_published": true,
     *       "created_at": "2026-06-01T10:00:00.000000Z",
     *       "author": {
     *         "username": "operator"
     *       }
     *     }
     *   ],
     *   "links": {},
     *   "meta": {}
     * }
     */
    public function adminIndex(Request $request)
    {
        $query = InformasiPublik::with('author');

        if ($request->has('is_published')) {
            $query->where('is_published', $request->boolean('is_published'));
        }

        $informasi = $query->orderByDesc('created_at')->paginate(20);

        return response()->json($informasi);
    }

    /**
     * [Admin] Buat Informasi
     * 
     * Membuat informasi publik baru (admin only).
     * 
     * @authenticated
     * 
     * @bodyParam judul string required Judul informasi. Example: Musyawarah Gampong 2026
     * @bodyParam konten string required Konten informasi (HTML). Example: <p>Akan dilaksanakan musyawarah...</p>
     * @bodyParam kategori string required Kategori informasi. Example: Pengumuman
     * @bodyParam cover_image string URL cover image. Example: https://storage.com/cover.jpg
     * @bodyParam is_published boolean Status publikasi. Example: true
     * 
     * @response 201 {
     *   "message": "Informasi berhasil dibuat",
     *   "data": {
     *     "id": 1,
     *     "judul": "Musyawarah Gampong 2026",
     *     "slug": "musyawarah-gampong-2026",
     *     "konten": "<p>Akan dilaksanakan musyawarah...</p>",
     *     "kategori": "Pengumuman",
     *     "cover_image": "https://storage.com/cover.jpg",
     *     "is_published": true,
     *     "author_id": 1,
     *     "created_at": "2026-06-01T10:00:00.000000Z"
     *   }
     * }
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|string|max:50',
            'cover_image' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $admin = $request->user();

        $informasi = InformasiPublik::create([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'kategori' => $request->kategori,
            'cover_image' => $request->cover_image,
            'is_published' => $request->boolean('is_published', false),
            'author_id' => $admin->id,
        ]);

        // Audit log
        AuditLog::log('admin', $admin->id, 'create', 'informasi_publik', $informasi->id, null, $informasi->toArray());

        return response()->json([
            'message' => 'Informasi berhasil dibuat',
            'data' => $informasi,
        ], 201);
    }

    /**
     * [Admin] Update Informasi
     * 
     * Mengupdate informasi publik (admin only).
     * 
     * @authenticated
     * 
     * @urlParam id integer required ID informasi. Example: 1
     * @bodyParam judul string Judul informasi. Example: Musyawarah Gampong 2026 (Updated)
     * @bodyParam konten string Konten informasi (HTML). Example: <p>Update konten...</p>
     * @bodyParam kategori string Kategori informasi. Example: Berita
     * @bodyParam cover_image string URL cover image. Example: https://storage.com/cover-new.jpg
     * @bodyParam is_published boolean Status publikasi. Example: false
     * 
     * @response 200 {
     *   "message": "Informasi berhasil diupdate",
     *   "data": {
     *     "id": 1,
     *     "judul": "Musyawarah Gampong 2026 (Updated)",
     *     "slug": "musyawarah-gampong-2026-updated",
     *     "konten": "<p>Update konten...</p>",
     *     "kategori": "Berita",
     *     "is_published": false,
     *     "updated_at": "2026-06-01T11:00:00.000000Z"
     *   }
     * }
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'string|max:255',
            'konten' => 'string',
            'kategori' => 'string|max:50',
            'cover_image' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $informasi = InformasiPublik::findOrFail($id);
        $admin = $request->user();

        $oldData = $informasi->toArray();
        
        $informasi->update($request->only([
            'judul',
            'konten',
            'kategori',
            'cover_image',
            'is_published',
        ]));

        // Audit log
        AuditLog::log('admin', $admin->id, 'update', 'informasi_publik', $informasi->id, $oldData, $informasi->toArray());

        return response()->json([
            'message' => 'Informasi berhasil diupdate',
            'data' => $informasi,
        ]);
    }

    /**
     * [Admin] Hapus Informasi
     * 
     * Menghapus informasi publik (admin only).
     * 
     * @authenticated
     * 
     * @urlParam id integer required ID informasi. Example: 1
     * 
     * @response 200 {
     *   "message": "Informasi berhasil dihapus"
     * }
     */
    public function destroy($id)
    {
        $informasi = InformasiPublik::findOrFail($id);
        $admin = request()->user();

        $oldData = $informasi->toArray();
        $informasi->delete();

        // Audit log
        AuditLog::log('admin', $admin->id, 'delete', 'informasi_publik', $id, $oldData, null);

        return response()->json([
            'message' => 'Informasi berhasil dihapus',
        ]);
    }
}
