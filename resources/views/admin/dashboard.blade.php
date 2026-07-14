@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    {{-- Welcome Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-on-surface">Dashboard Overview</h2>
        <p class="text-sm text-on-surface-variant">Pemantauan real-time aktivitas barang hilang & temuan kampus.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-2xl">inventory_2</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total Hilang</p>
                <p class="text-3xl font-bold">{{ $totalLost }}</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-secondary-container/40 flex items-center justify-center text-secondary">
                <span class="material-symbols-outlined text-2xl">find_in_page</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total Ditemukan</p>
                <p class="text-3xl font-bold">{{ $totalFound }}</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-error-container/50 flex items-center justify-center text-error">
                <span class="material-symbols-outlined text-2xl">pending_actions</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Klaim Pending</p>
                <p class="text-3xl font-bold text-error">{{ $pendingClaims }}</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-2xl">task_alt</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kasus Selesai</p>
                <p class="text-3xl font-bold">{{ $resolvedCases }}</p>
            </div>
        </div>

    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- Bar Chart: Reports per Month --}}
        <div class="lg:col-span-2 bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold">Laporan per Bulan</h3>
                <div class="flex gap-3 text-xs">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-primary"></span> Hilang</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-secondary/50"></span> Ditemukan</span>
                </div>
            </div>

            <div class="h-64 flex items-end justify-between gap-4 px-2 border-b border-outline-variant pb-2">
                @foreach ($months as $m)
                    <div class="flex-1 flex flex-col items-center gap-1 h-full justify-end">
                        <div class="w-4 bg-primary rounded-t-sm" style="height: {{ $m['lost_percent'] }}%"></div>
                        <div class="w-4 bg-secondary/50 rounded-t-sm" style="height: {{ $m['found_percent'] }}%"></div>
                        <span class="text-[10px] text-on-surface-variant mt-2">{{ $m['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Donut Chart: Most Reported Categories --}}
        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant flex flex-col">
            <h3 class="text-lg font-semibold mb-6">Kategori Paling Sering Dilaporkan</h3>

            <div class="flex-1 flex items-center justify-center relative py-4">
                <svg class="w-48 h-48 -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" fill="transparent" r="40" stroke="#e1e2ec" stroke-width="12"></circle>
                    <circle cx="50" cy="50" fill="transparent" r="40" stroke="#0058be" stroke-dasharray="251.2" stroke-dashoffset="62.8" stroke-width="12"></circle>
                    <circle cx="50" cy="50" fill="transparent" r="40" stroke="#505f76" stroke-dasharray="251.2" stroke-dashoffset="188.4" stroke-width="12"></circle>
                    <circle cx="50" cy="50" fill="transparent" r="40" stroke="#ba1a1a" stroke-dasharray="251.2" stroke-dashoffset="230" stroke-width="12"></circle>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-3xl font-bold">{{ $totalItemsForDonut }}</span>
                    <span class="text-xs text-on-surface-variant">Barang</span>
                </div>
            </div>

            <div class="space-y-3 mt-4">
                @php $colors = ['bg-primary', 'bg-secondary', 'bg-error']; @endphp

                @forelse ($topCategories as $i => $cat)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full {{ $colors[$i] ?? 'bg-outline' }}"></span>
                            <span class="text-sm">{{ $cat->category->name ?? '-' }}</span>
                        </div>
                        <span class="text-sm font-semibold">
                            {{ $totalItemsForDonut > 0 ? round(($cat->total / $totalItemsForDonut) * 100) : 0 }}%
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-on-surface-variant">Belum ada data.</p>
                @endforelse
            </div>
        </div>

    </div>

{{-- Recent Activity Table --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
    <div class="p-6 border-b border-outline-variant flex items-center justify-between">
        <h3 class="text-lg font-semibold">Aktivitas Terbaru</h3>
        <div class="flex gap-2">
            <button class="bg-surface-container-low text-on-surface-variant px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition-colors duration-150 hover:bg-surface-container active:scale-95">
                <span class="material-symbols-outlined text-sm">filter_list</span> Filter
            </button>
            <button id="exportBtn"
                class="bg-primary text-on-primary px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition-all duration-150 hover:opacity-90 hover:shadow-md active:scale-95 active:opacity-80 disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="material-symbols-outlined text-sm">download</span> Export
            </button>
        </div>
    </div>
    {{-- ... isi table activityTable tetap di sini ... --}}
</div>

<script>
document.getElementById('exportBtn').addEventListener('click', function () {
    const btn = this;
    const originalHTML = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">progress_activity</span> Exporting...';

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFontSize(16);
    doc.text('Aktivitas Terbaru', 14, 22);

    const head = [['Barang', 'Tipe', 'Pelapor', 'Tanggal', 'Status']];
    const body = [];

    document.querySelectorAll('#activityTable tbody tr').forEach(tr => {
        const cells = tr.querySelectorAll('td');
        if (cells.length < 6) return; // skip baris "Belum ada laporan"

        const namaBarang = cells[0].querySelector('p.font-medium')?.textContent.trim() || '';
        const tipe = cells[1].textContent.trim();
        const pelapor = cells[2].textContent.trim();
        const tanggal = cells[3].textContent.trim();
        const status = cells[4].textContent.trim();
        // cells[5] (Aksi/tombol "Lihat") sengaja dilewati, tidak relevan untuk PDF

        body.push([namaBarang, tipe, pelapor, tanggal, status]);
    });

    doc.autoTable({
        head: head,
        body: body,
        startY: 30,
        theme: 'grid',
        headStyles: {
            fillColor: [41, 98, 255],
            textColor: 255,
            fontStyle: 'bold',
        },
        alternateRowStyles: {
            fillColor: [245, 245, 245],
        },
        styles: {
            fontSize: 10,
            cellPadding: 3,
        },
    });

    doc.save('aktivitas_terbaru.pdf');

    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }, 500);
});</script>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="activityTable" class="w-full text-left border-collapse"
            class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Barang</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Tipe</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Pelapor</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Tanggal</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">

                    @php
                        $statusColor = [
                            'pending' => 'bg-amber-100 text-amber-700',
                            'verified' => 'bg-blue-100 text-blue-700',
                            'claimed' => 'bg-green-100 text-green-700',
                            'resolved' => 'bg-green-100 text-green-700',
                            'rejected' => 'bg-red-100 text-red-700',
                        ];

                        $typeColor = [
                            'hilang' => 'bg-primary/10 text-primary',
                            'temuan' => 'bg-secondary/10 text-secondary',
                        ];

                        $typeIcon = [
                            'hilang' => 'search',
                            'temuan' => 'inventory_2',
                        ];
                    @endphp

                    @forelse ($recentActivities as $a)
                        <tr class="hover:bg-surface-container-low/50">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant">
                                        <span class="material-symbols-outlined text-lg">{{ $typeIcon[$a->type] ?? 'inventory_2' }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">{{ $a->name }}</p>
                                        <p class="text-xs text-on-surface-variant">{{ $a->category->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase {{ $typeColor[$a->type] ?? '' }}">
                                    {{ $a->type }}
                                </span>
                            </td>
                            <td class="p-4 text-sm">{{ $a->user->name ?? '-' }}</td>
                            <td class="p-4 text-sm text-on-surface-variant">{{ $a->date->translatedFormat('d M Y') }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase {{ $statusColor[$a->status] ?? '' }}">
                                    {{ $a->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
    <a href="{{ route('items.show', $a) }}"
       class="text-primary hover:bg-primary/5 px-3 py-1 rounded-md text-sm font-semibold inline-block transition-colors">
        Lihat
    </a>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-sm text-on-surface-variant">
                                Belum ada laporan.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </id=>
        </div>

        <div class="p-4 bg-surface-container-low/30 flex items-center justify-between border-t border-outline-variant">
            <p class="text-xs text-on-surface-variant">
                Menampilkan {{ $recentActivities->count() }} dari {{ \App\Models\Item::count() }} laporan
            </p>
            <div class="flex gap-2">
                <button class="p-2 rounded-lg hover:bg-surface-container-low" disabled>
                    <span class="material-symbols-outlined text-sm">chevron_left</span>
                </button>
                <button class="p-2 rounded-lg hover:bg-surface-container-low">
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </button>
            </div>
        </div>
    </div>

@endsection
