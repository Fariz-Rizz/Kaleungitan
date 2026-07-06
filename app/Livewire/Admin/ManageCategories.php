<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ManageCategories extends Component
{
    use WithPagination;

    public string $search = '';

    // Untuk modal form (dipakai sama untuk Tambah & Edit)
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $name = '';

    // Untuk modal konfirmasi hapus
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $this->editingId,
        ];
    }

    protected $messages = [
        'name.required' => 'Nama kategori wajib diisi.',
        'name.unique' => 'Nama kategori ini sudah ada.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['name', 'editingId']);
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $category = Category::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'editingId']);
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        Category::updateOrCreate(
            ['id' => $this->editingId],
            ['name' => $this->name]
        );

        $this->closeModal();
        session()->flash('success', $this->editingId ? 'Kategori berhasil diperbarui.' : 'Kategori berhasil ditambahkan.');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $category = Category::withCount('items')->findOrFail($this->deletingId);

        if ($category->items_count > 0) {
            session()->flash('error', 'Kategori ini tidak bisa dihapus karena masih dipakai oleh ' . $category->items_count . ' laporan barang.');
            $this->showDeleteModal = false;
            $this->deletingId = null;

            return;
        }

        $category->delete();

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'Kategori berhasil dihapus.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $categories = Category::withCount('items')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.manage-categories', compact('categories'));
    }
}
