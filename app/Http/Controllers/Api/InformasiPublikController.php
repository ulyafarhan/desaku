<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InformasiPublik;
use App\Models\AuditLog;
use Illuminate\Http\Request;

/**
 * Controller untuk mengelola data artikel berita dan pengumuman gampong lewat API.
 */
class InformasiPublikController extends Controller
{
    /**
     * Mengambil daftar seluruh artikel informasi publik yang telah terbit.
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
     * Menampilkan detail informasi publik tertentu berdasarkan slug.
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
     * Mengambil daftar informasi publik untuk keperluan panel admin (termasuk draf).
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
     * Menyimpan artikel informasi publik baru ke database.
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

        AuditLog::log('admin', $admin->id, 'create', 'informasi_publik', $informasi->id, null, $informasi->toArray());

        return response()->json([
            'message' => 'Informasi berhasil dibuat',
            'data' => $informasi,
        ], 201);
    }

    /**
     * Memperbarui detail artikel informasi publik tertentu berdasarkan ID.
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

        AuditLog::log('admin', $admin->id, 'update', 'informasi_publik', $informasi->id, $oldData, $informasi->toArray());

        return response()->json([
            'message' => 'Informasi berhasil diupdate',
            'data' => $informasi,
        ]);
    }

    /**
     * Menghapus artikel informasi publik tertentu dari database.
     */
    public function destroy($id)
    {
        $informasi = InformasiPublik::findOrFail($id);
        $admin = request()->user();

        $oldData = $informasi->toArray();
        $informasi->delete();

        AuditLog::log('admin', $admin->id, 'delete', 'informasi_publik', $id, $oldData, null);

        return response()->json([
            'message' => 'Informasi berhasil dihapus',
        ]);
    }
}
