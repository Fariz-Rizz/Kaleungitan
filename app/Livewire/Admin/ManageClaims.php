<?php

namespace App\Livewire\Admin;

use App\Models\Claim;
use App\Notifications\ClaimStatusNotification;
use Livewire\Component;
use Livewire\WithPagination;

class ManageClaims extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';

    // Untuk modal
    public ?Claim $selectedClaim = null;
    public bool $showModal = false;

    protected $paginationTheme = 'tailwind';

    public function updating($property)
    {
        if (in_array($property, ['search', 'filterStatus'])) {
            $this->resetPage();
        }
    }

    public function openDetail($claimId)
    {
        $this->selectedClaim = Claim::with(['item.category', 'item.user', 'user'])->findOrFail($claimId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedClaim = null;
    }

    public function approve($claimId)
    {
        $claim = Claim::with(['item', 'user'])->findOrFail($claimId);

        // Setujui klaim ini
        $claim->update(['status' => 'approved']);

        // Tandai barang terkait sebagai sudah diklaim
        $claim->item->update(['status' => 'claimed']);

        // Otomatis tolak klaim lain yang masih pending untuk barang yang sama
        // (karena barang cuma bisa dimiliki satu orang)
        $rejectedClaims = Claim::with('user')
            ->where('item_id', $claim->item_id)
            ->where('id', '!=', $claim->id)
            ->where('status', 'pending')
            ->get();

        foreach ($rejectedClaims as $rejected) {
            $rejected->update(['status' => 'rejected']);
            $rejected->user?->notify(new ClaimStatusNotification($rejected, 'rejected'));
        }

        $claim->user?->notify(new ClaimStatusNotification($claim, 'approved'));

        $this->closeModal();
        session()->flash('success', 'Klaim disetujui & barang ditandai sebagai selesai diklaim.');
    }

    public function reject($claimId)
    {
        $claim = Claim::with(['item', 'user'])->findOrFail($claimId);
        $claim->update(['status' => 'rejected']);

        $claim->user?->notify(new ClaimStatusNotification($claim, 'rejected'));

        $this->closeModal();
        session()->flash('success', 'Klaim ditolak.');
    }

    public function render()
    {
        $claims = Claim::query()
            ->with(['item', 'user'])
            ->when($this->search, function ($q) {
                $q->whereHas('item', fn ($iq) => $iq->where('name', 'like', "%{$this->search}%"))
                  ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$this->search}%"));
            })
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-claims', compact('claims'));
    }
}
