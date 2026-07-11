<?php

namespace App\Livewire\User;

use App\Models\Claim;
use App\Models\Item;
use Livewire\Component;

class Dashboard extends Component
{
    public string $activeTab = 'reports';

    public function setTab($tab)
    {
        $this->activeTab = $tab;
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
