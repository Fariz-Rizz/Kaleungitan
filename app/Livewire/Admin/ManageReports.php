<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Item;
use App\Notifications\ItemStatusNotification;
use Livewire\Component;
use Livewire\WithPagination;

class ManageReports extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterType = '';
    public string $filterStatus = '';
    public string $filterCategory = '';

    // Untuk modal
    public ?Item $selectedItem = null;
    public bool $showModal = false;
    public string $rejectReason = '';

    protected $paginationTheme = 'tailwind';

    // Reset ke halaman 1 tiap kali filter berubah
    public function updating($property)
    {
        if (in_array($property, ['search', 'filterType', 'filterStatus', 'filterCategory'])) {
            $this->resetPage();
        }
    }

    public function openDetail($itemId)
    {
        $this->selectedItem = Item::with(['user', 'category'])->findOrFail($itemId);
        $this->showModal = true;
        $this->rejectReason = '';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedItem = null;
    }

    public function approve($itemId)
    {
        $item = Item::findOrFail($itemId);
        $item->update(['status' => 'verified']);

        $item->user?->notify(new ItemStatusNotification($item, 'verified'));

        $this->closeModal();
        session()->flash('success', 'Laporan berhasil disetujui.');
    }

    public function reject($itemId)
    {
        $this->validate([
            'rejectReason' => 'required|min:5',
        ], [
            'rejectReason.required' => 'Alasan penolakan wajib diisi.',
            'rejectReason.min' => 'Alasan minimal 5 karakter.',
        ]);

        $item = Item::findOrFail($itemId);
        $item->update(['status' => 'rejected']);

        $item->user?->notify(new ItemStatusNotification($item, 'rejected', $this->rejectReason));

        $this->closeModal();
        session()->flash('success', 'Laporan berhasil ditolak.');
    }

    public function archive($itemId)
    {
        $item = Item::findOrFail($itemId);
        $item->update(['status' => 'resolved']);

        $item->user?->notify(new ItemStatusNotification($item, 'resolved'));

        $this->closeModal();
        session()->flash('success', 'Laporan berhasil diarsipkan.');
    }

    public function render()
    {
        $items = Item::query()
            ->with(['user', 'category'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->filterType, fn ($q) => $q->where('type', $this->filterType))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterCategory, fn ($q) => $q->where('category_id', $this->filterCategory))
            ->latest()
            ->paginate(10);

        $categories = Category::orderBy('name')->get();

        return view('livewire.admin.manage-reports', compact('items', 'categories'));
    }
}
