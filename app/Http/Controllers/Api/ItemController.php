<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * GET /api/items
     * Menampilkan daftar barang (hilang/temuan) dengan search, filter, dan pagination.
     */
    public function index(Request $request)
    {
        $items = Item::query()
            ->with(['category', 'user'])
            ->when($request->query('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->query('type'), fn($query, $type) => $query->where('type', $type))
            ->when($request->query('status'), fn($query, $status) => $query->where('status', $status))
            ->when($request->query('category_id'), fn($query, $categoryId) => $query->where('category_id', $categoryId))
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return ItemResource::collection($items);
    }

    /**
     * GET /api/items/{item}
     * Menampilkan detail satu barang.
     */
    public function show(Item $item)
    {
        $item->load(['category', 'user']);
        $item->loadCount('claims');

        return new ItemResource($item);
    }

    /**
     * POST /api/items
     * Membuat laporan barang baru (hilang/temuan).
     *
     * Catatan: endpoint ini bersifat publik (tanpa auth), sehingga user_id
     * wajib dikirim langsung di body request. Jika nanti autentikasi API
     * (mis. Sanctum) sudah ditambahkan, baris ini sebaiknya diganti dengan
     * auth()->id() agar pelapor otomatis terisi dari user yang login.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:hilang,temuan',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('items', 'public');
        }

        $data['status'] = 'pending';

        $item = Item::create($data);
        $item->load(['category', 'user']);

        return (new ItemResource($item))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * PUT/PATCH /api/items/{item}
     * Memperbarui laporan barang.
     */
    public function update(Request $request, Item $item)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|exists:categories,id',
            'type' => 'sometimes|in:hilang,temuan',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'location' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'status' => 'sometimes|in:pending,verified,claimed,resolved,rejected',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('photo')) {
            if ($item->photo) {
                Storage::disk('public')->delete($item->photo);
            }
            $data['photo'] = $request->file('photo')->store('items', 'public');
        }

        $item->update($data);
        $item->load(['category', 'user']);

        return new ItemResource($item);
    }

    /**
     * DELETE /api/items/{item}
     * Menghapus laporan barang.
     */
    public function destroy(Item $item)
    {
        if ($item->photo) {
            Storage::disk('public')->delete($item->photo);
        }

        $item->delete();

        return response()->json([
            'message' => 'Laporan berhasil dihapus.',
        ]);
    }
}
