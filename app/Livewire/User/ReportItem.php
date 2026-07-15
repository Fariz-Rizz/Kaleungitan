<?php

namespace App\Livewire\User;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use App\Notifications\NewItemReportNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReportItem extends Component
{
    use WithFileUploads;

    public ?Item $item = null; // terisi kalau mode edit

    public string $type; // 'hilang' atau 'temuan'

    public string $name = '';
    public string $category_id = '';
    public string $description = '';
    public string $location = '';
    public string $date = '';
    public $photo = null;
    public ?string $existingPhoto = null; // foto lama saat mode edit

    public function mount(?string $type = null, ?Item $item = null)
    {
        if ($item) {
            $this->item = $item;
            $this->type = $item->type;
            $this->name = $item->name;
            $this->category_id = (string) $item->category_id;
            $this->description = $item->description;
            $this->location = $item->location;
            $this->date = $item->date->format('Y-m-d');
            $this->existingPhoto = $item->photo;
        } else {
            $this->type = $type;
            $this->date = now()->format('Y-m-d');
        }
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
        $this->existingPhoto = null;
    }

    public function save()
    {
        $this->validate();

        // Mode EDIT
        if ($this->item) {
            $photoPath = $this->existingPhoto;

            if ($this->photo) {
                if ($this->item->photo) {
                    Storage::disk('public')->delete($this->item->photo);
                }
                $photoPath = $this->photo->store('items', 'public');
            } elseif (! $this->existingPhoto && $this->item->photo) {
                // foto lama sengaja dihapus user dan tidak diganti
                Storage::disk('public')->delete($this->item->photo);
                $photoPath = null;
            }

            $this->item->update([
                'category_id' => $this->category_id,
                'name' => $this->name,
                'description' => $this->description,
                'location' => $this->location,
                'date' => $this->date,
                'photo' => $photoPath,
            ]);

            session()->flash('success', 'Laporan berhasil diperbarui.');

            return redirect()->route('items.show', $this->item->id);
        }

        // Mode CREATE
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

        // Beri tahu semua admin bahwa ada laporan baru yang perlu diverifikasi
        $item->setRelation('user', auth()->user());
        Notification::send(
            User::admins()->where('id', '!=', auth()->id())->get(),
            new NewItemReportNotification($item)
        );

        session()->flash('success', 'Laporan berhasil dikirim! Menunggu verifikasi admin.');

        return redirect()->route('items.show', $item->id);
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();

        return view('livewire.user.report-item', compact('categories'));
    }
}
