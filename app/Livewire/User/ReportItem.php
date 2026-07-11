<?php

namespace App\Livewire\User;

use App\Models\Category;
use App\Models\Item;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReportItem extends Component
{
    use WithFileUploads;

    public string $type; // 'hilang' atau 'temuan'

    public string $name = '';
    public string $category_id = '';
    public string $description = '';
    public string $location = '';
    public string $date = '';
    public $photo = null;

    public function mount(string $type)
    {
        $this->type = $type;
        $this->date = now()->format('Y-m-d');
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:10',
            'location' => 'required|string|max:255',
            'date' => 'required|date|before_or_equal:today',
            'photo' => 'nullable|image|max:2048', // maks 2MB
        ];
    }

    protected $messages = [
        'name.required' => 'Nama barang wajib diisi.',
        'category_id.required' => 'Kategori wajib dipilih.',
        'description.required' => 'Deskripsi wajib diisi.',
        'description.min' => 'Deskripsi minimal 10 karakter, semakin detail semakin membantu.',
        'location.required' => 'Lokasi wajib diisi.',
        'date.required' => 'Tanggal wajib diisi.',
        'date.before_or_equal' => 'Tanggal tidak boleh di masa depan.',
        'photo.image' => 'File harus berupa gambar (jpg, png, dll).',
        'photo.max' => 'Ukuran foto maksimal 2MB.',
    ];

    public function removePhoto()
    {
        $this->photo = null;
    }

    public function save()
    {
        $this->validate();

        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('items', 'public');
        }

        $item = Item::create([
            'user_id' => auth()->id(),
            'category_id' => $this->category_id,
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->description,
            'location' => $this->location,
            'date' => $this->date,
            'photo' => $photoPath,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Laporan berhasil dikirim! Menunggu verifikasi admin.');

        return redirect()->route('items.show', $item->id);
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();

        return view('livewire.user.report-item', compact('categories'));
    }
}
