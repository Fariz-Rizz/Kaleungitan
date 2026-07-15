<?php

namespace App\Livewire\User;

use App\Models\Claim;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Dashboard extends Component
{
    public string $activeTab = 'reports';
    public ?string $successMessage = null;

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function deleteReport($itemId)
    {
        $item = Item::where('id', $itemId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        abort_unless($item->status === 'pending', 403, 'Laporan yang sudah diproses admin tidak bisa dihapus.');

        if ($item->photo) {
            Storage::disk('public')->delete($item->photo);
        }

        $item->delete();

        $this->successMessage = 'Laporan berhasil dihapus.';
    }

    public function render()
    {
        $userId = auth()->id();

        // Stat cards
        $totalReports = Item::where('user_id', $userId)->count();
        $pendingReports = Item::where('user_id', $userId)->where('status', 'pending')->count();
        $pendingClaims = Claim::where('user_id', $userId)->where('status', 'pending')->count();
        $resolvedReports = Item::where('user_id', $userId)->where('status', 'resolved')->count();

        // My Reports
        $myReports = Item::with('category')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        // My Claims
        $myClaims = Claim::with(['item.category'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return view('livewire.user.dashboard', compact(
            'totalReports',
            'pendingReports',
            'pendingClaims',
            'resolvedReports',
            'myReports',
            'myClaims'
        ));
    }
}
