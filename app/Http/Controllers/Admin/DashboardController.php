<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Status yang dipilih dari dropdown Filter (opsional)
        $status = $request->query('status');
        // Stat Cards
        $totalLost = Item::where('type', 'hilang')->count();
        $totalFound = Item::where('type', 'temuan')->count();
        $pendingClaims = Claim::where('status', 'pending')->count();
        $resolvedCases = Item::where('status', 'resolved')->count();

        // Reports per Month (6 bulan terakhir)
        $months = collect(range(5, 0))->map(function ($i) {
            $date = Carbon::now()->subMonths($i);

            return [
                'label' => $date->translatedFormat('M'),
                'lost' => Item::where('type', 'hilang')
                    ->whereYear('date', $date->year)
                    ->whereMonth('date', $date->month)
                    ->count(),
                'found' => Item::where('type', 'temuan')
                    ->whereYear('date', $date->year)
                    ->whereMonth('date', $date->month)
                    ->count(),
            ];
        });

        // Normalisasi tinggi bar chart jadi persen (biar tampilannya proporsional)
        $maxCount = $months->flatMap(fn($m) => [$m['lost'], $m['found']])->max();
        $maxCount = $maxCount > 0 ? $maxCount : 1;

        $months = $months->map(function ($m) use ($maxCount) {
            $m['lost_percent'] = round(($m['lost'] / $maxCount) * 100);
            $m['found_percent'] = round(($m['found'] / $maxCount) * 100);

            return $m;
        });

        // Kategori paling sering dilaporkan (top 3)
        $topCategories = Item::selectRaw('category_id, count(*) as total')
            ->with('category')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->take(3)
            ->get();

        $totalItemsForDonut = Item::count();

        // Aktivitas Terbaru (bisa difilter berdasarkan status)
        $recentActivities = Item::with(['user', 'category'])
            ->when($status, fn($query) => $query->where('status', $status))
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalLost',
            'totalFound',
            'pendingClaims',
            'resolvedCases',
            'months',
            'topCategories',
            'totalItemsForDonut',
            'recentActivities',
            'status'
        ));
    }
}
