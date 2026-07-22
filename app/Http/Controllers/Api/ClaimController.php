<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClaimResource;
use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClaimController extends Controller
{
    /**
     * GET /api/claims
     * Menampilkan daftar klaim dengan search, filter status, dan pagination.
     */
    public function index(Request $request)
    {
        $claims = Claim::query()
            ->with(['item.category', 'user'])
            ->when($request->query('search'), function ($query, $search) {
                $query->whereHas('item', fn($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->when($request->query('status'), fn($query, $status) => $query->where('status', $status))
            ->when($request->query('item_id'), fn($query, $itemId) => $query->where('item_id', $itemId))
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return ClaimResource::collection($claims);
    }

    /**
     * GET /api/claims/{claim}
     * Menampilkan detail satu klaim.
     */
    public function show(Claim $claim)
    {
        $claim->load(['item.category', 'user']);

        return new ClaimResource($claim);
    }

    /**
     * POST /api/claims
     * Mengajukan klaim baru atas sebuah barang.
     *
     * Catatan: endpoint ini bersifat publik (tanpa auth), sehingga user_id
     * wajib dikirim langsung di body request. Jika nanti autentikasi API
     * (mis. Sanctum) sudah ditambahkan, baris ini sebaiknya diganti dengan
     * auth()->id() agar pengklaim otomatis terisi dari user yang login.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
            'user_id' => 'required|exists:users,id',
            'description' => 'required|string|min:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $item = Item::findOrFail($data['item_id']);

        if ($item->user_id === (int) $data['user_id']) {
            return response()->json([
                'message' => 'Kamu tidak bisa mengklaim laporanmu sendiri.',
            ], 422);
        }

        if (in_array($item->status, ['claimed', 'resolved'])) {
            return response()->json([
                'message' => 'Laporan ini sudah diselesaikan.',
            ], 422);
        }

        if (Claim::where('item_id', $item->id)->where('user_id', $data['user_id'])->exists()) {
            return response()->json([
                'message' => 'Kamu sudah pernah mengirim klaim untuk barang ini.',
            ], 422);
        }

        $claim = Claim::create([
            'item_id' => $item->id,
            'user_id' => $data['user_id'],
            'description' => $data['description'],
            'status' => 'pending',
        ]);

        $claim->load(['item.category', 'user']);

        return (new ClaimResource($claim))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * PATCH /api/claims/{claim}
     * Memperbarui deskripsi/status klaim secara umum.
     */
    public function update(Request $request, Claim $claim)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'sometimes|string|min:15',
            'status' => 'sometimes|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $claim->update($validator->validated());
        $claim->load(['item.category', 'user']);

        return new ClaimResource($claim);
    }

    /**
     * POST /api/claims/{claim}/approve
     * Menyetujui klaim -> status klaim jadi approved, status item jadi claimed.
     */
    public function approve(Claim $claim)
    {
        $claim->update(['status' => 'approved']);
        $claim->item()->update(['status' => 'claimed']);

        $claim->load(['item.category', 'user']);

        return new ClaimResource($claim);
    }

    /**
     * POST /api/claims/{claim}/reject
     * Menolak klaim -> status klaim jadi rejected.
     */
    public function reject(Claim $claim)
    {
        $claim->update(['status' => 'rejected']);
        $claim->load(['item.category', 'user']);

        return new ClaimResource($claim);
    }

    /**
     * DELETE /api/claims/{claim}
     * Menghapus klaim.
     */
    public function destroy(Claim $claim)
    {
        $claim->delete();

        return response()->json([
            'message' => 'Klaim berhasil dihapus.',
        ]);
    }
}
