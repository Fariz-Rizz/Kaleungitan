<?php

namespace App\Livewire\User;

use App\Models\Category;
use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class BrowseItems extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterType = '';
    public string $filterCategory = '';
    public string $sortBy = 'newest';

    protected $paginationTheme = 'tailwind';

    public function updating($property)
    {
        if (in_array($property, ['search', 'filterType', 'filterCategory', 'sortBy'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $items = Item::query()
            ->with(['category', 'user'])
            // Sembunyikan laporan yang ditolak admin dari daftar publik
            ->where('status', '!=', 'rejected')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->when($this->filterCategory, fn($q) => $q->where('category_id', $this->filterCategory))
            ->when($this->sortBy === 'newest', fn($q) => $q->latest())
            ->when($this->sortBy === 'oldest', fn($q) => $q->oldest())
            ->paginate(12);

        $categories = Category::orderBy('name')->get();

        return view('livewire.user.browse-items', compact('items', 'categories'));
    }
}
