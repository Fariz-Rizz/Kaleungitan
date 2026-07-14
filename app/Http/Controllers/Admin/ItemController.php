<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function show(Item $item): View
    {
        $item->load(['category', 'user', 'claims.user']);

        return view('admin.item-detail', compact('item'));
    }
}
