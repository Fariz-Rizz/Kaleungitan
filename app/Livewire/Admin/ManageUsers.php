<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ManageUsers extends Component
{
    use WithPagination;

    public string $search = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);

        // Safety: jangan biarkan admin men-suspend akun admin (termasuk dirinya sendiri)
        if ($user->role === 'admin') {
            session()->flash('error', 'Akun admin tidak bisa disuspend.');

            return;
        }

        $user->forceFill(['is_active' => ! $user->is_active])->save();

        session()->flash('success', $user->is_active
            ? 'User berhasil diaktifkan kembali.'
            : 'User berhasil disuspend.');
    }

    public function render()
    {
        $users = User::withCount(['items', 'claims'])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.manage-users', compact('users'));
    }
}
