<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InformasiPublik;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class InformasiPublikController extends Controller
{
    public function index(Request $request)
    {
        $query = InformasiPublik::published()->with('author');

        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $informasi = $query->orderByDesc('created_at')->paginate(10);

        return response()->json($informasi);
    }

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

    public function adminIndex(Request $request)
    {
        $query = InformasiPublik::with('author');

        if ($request->has('is_published')) {
            $query->where('is_published', $request->boolean('is_published'));
        }

        $informasi = $query->orderByDesc('created_at')->paginate(20);

        return response()->json($informasi);
    }

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
