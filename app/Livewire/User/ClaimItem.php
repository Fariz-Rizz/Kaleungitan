<?php

namespace App\Livewire\User;

use App\Models\Item;
use App\Models\Claim;
use Livewire\Component;

class ClaimItem extends Component
{
    public Item $item;
    public string $description = '';

    public function mount(Item $item)
{
    $item->loadMissing('category');
    $this->item = $item;

    // Guard: pastikan barang ini benar bisa diklaim
    abort_if($this->item->type !== 'temuan', 403, 'Barang ini bukan barang temuan.');
    abort_if($this->item->user_id === auth()->id(), 403, 'Kamu tidak bisa mengklaim laporanmu sendiri.');
    abort_if(in_array($this->item->status, ['claimed', 'resolved']), 403, 'Barang ini sudah diklaim.');

    if (Claim::where('item_id', $this->item->id)->where('user_id', auth()->id())->exists()) {
        abort(403, 'Kamu sudah pernah mengajukan klaim untuk barang ini.');
    }
}

    protected function rules()
    {
        return [
            'description' => 'required|string|min:15',
        ];
    }

    protected $messages = [
        'description.required' => 'Deskripsi bukti kepemilikan wajib diisi.',
        'description.min' => 'Jelaskan lebih detail (minimal 15 karakter) agar admin bisa memverifikasi.',
    ];

    public function submit()
    {
        $this->validate();

        Claim::create([
            'item_id' => $this->item->id,
            'user_id' => auth()->id(),
            'description' => $this->description,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Klaim berhasil diajukan! Admin akan segera memverifikasi.');

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.user.claim-item');
    }
}
