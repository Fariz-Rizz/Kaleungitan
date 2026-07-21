<?php

namespace App\Livewire\User;

use App\Models\Claim;
use App\Models\Item;
use App\Notifications\FoundReportStatusNotification;
use Livewire\Component;

class ResolveFoundReports extends Component
{
    public Item $item;

    public function mount(Item $item)
    {
        $this->item = $item;
    }

    public function getReportsProperty()
    {
        return Claim::with('user')
            ->where('item_id', $this->item->id)
            ->where('status', 'pending')
            ->latest()
            ->get();
    }

    /**
     * Pemilik laporan mengonfirmasi: ya, ini barangku, sudah ditemukan.
     */
    public function confirmFound($claimId)
    {
        $claim = Claim::with(['user', 'item'])->findOrFail($claimId);
        abort_if($claim->item->user_id !== auth()->id(), 403);

        $claim->update(['status' => 'approved']);
        $claim->item->update(['status' => 'resolved']);

        // Laporan penemuan lain yang masih pending untuk barang yang sama otomatis ditolak,
        // karena barang sudah dipastikan ketemu lewat laporan ini
        $others = Claim::with('user')
            ->where('item_id', $claim->item_id)
            ->where('id', '!=', $claim->id)
            ->where('status', 'pending')
            ->get();

        foreach ($others as $other) {
            $other->update(['status' => 'rejected']);
            $other->user?->notify(new FoundReportStatusNotification($other, 'rejected'));
        }

        $claim->user?->notify(new FoundReportStatusNotification($claim, 'approved'));

        $this->item->refresh();
        session()->flash('success', 'Laporan dikonfirmasi. Barang ditandai selesai/ditemukan.');
    }

    /**
     * Pemilik laporan menolak: bukan barang yang dimaksud, laporan tetap berlanjut.
     */
    public function rejectReport($claimId)
    {
        $claim = Claim::with(['user', 'item'])->findOrFail($claimId);
        abort_if($claim->item->user_id !== auth()->id(), 403);

        $claim->update(['status' => 'rejected']);
        $claim->user?->notify(new FoundReportStatusNotification($claim, 'rejected'));

        session()->flash('success', 'Laporan ditandai bukan barang yang dimaksud. Laporan kehilanganmu tetap berlanjut.');
    }

    public function render()
    {
        return view('livewire.user.resolve-found-reports');
    }
}
