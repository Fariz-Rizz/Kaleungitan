<?php

namespace App\Livewire\User;

use App\Models\Item;
use App\Models\Claim;
use App\Models\User;
use App\Notifications\AdminNewClaimNotification;
use App\Notifications\FoundReportNotification;
use App\Notifications\NewClaimNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ClaimItem extends Component
{
    public Item $item;
    public string $description = '';

    public function mount(Item $item)
    {
        $item->loadMissing('category');
        $this->item = $item;

        // Guard umum untuk kedua arah (klaim barang temuan & laporan penemuan barang hilang)
        abort_if($this->item->user_id === auth()->id(), 403, 'Kamu tidak bisa melapor pada laporanmu sendiri.');
        abort_if(in_array($this->item->status, ['claimed', 'resolved']), 403, 'Laporan ini sudah diselesaikan.');

        if (Claim::where('item_id', $this->item->id)->where('user_id', auth()->id())->exists()) {
            abort(403, 'Kamu sudah pernah mengirim laporan untuk barang ini.');
        }
    }

    protected function rules()
    {
        return [
            'description' => 'required|string|min:15',
        ];
    }

    protected $messages = [
        'description.required' => 'Deskripsi wajib diisi.',
        'description.min' => 'Jelaskan lebih detail (minimal 15 karakter) agar bisa diverifikasi.',
    ];

    public function submit()
    {
        $this->validate();

        $claim = Claim::create([
            'item_id' => $this->item->id,
            'user_id' => auth()->id(),
            'description' => $this->description,
            'status' => 'pending',
        ]);

        if ($this->item->type === 'temuan') {
            // Klaim kepemilikan atas barang temuan -> diverifikasi ADMIN
            $this->item->user?->notify(new NewClaimNotification($claim));

            Notification::send(
                User::admins()->where('id', '!=', auth()->id())->get(),
                new AdminNewClaimNotification($claim)
            );

            session()->flash('success', 'Klaim berhasil diajukan! Admin akan segera memverifikasi.');
        } else {
            // Laporan penemuan atas barang hilang -> diverifikasi langsung oleh PEMILIK LAPORAN
            $this->item->user?->notify(new FoundReportNotification($claim));

            session()->flash('success', 'Laporan penemuan berhasil dikirim! Pemilik laporan akan diberi tahu untuk verifikasi.');
        }

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.user.claim-item');
    }
}
