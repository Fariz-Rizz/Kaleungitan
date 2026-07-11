<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function show(Item $item): View
    {
        $item->load(['category', 'user']);

        // Cek apakah user yang login sudah pernah mengajukan klaim untuk barang ini
        $existingClaim = null;
        if (auth()->check()) {
            $existingClaim = $item->claims()->where('user_id', auth()->id())->first();
        }

        return view('user.item-detail', compact('item', 'existingClaim'));
    }

    public function claim(Item $item): View
    {
        return view('user.claim', compact('item'));
    }
}
