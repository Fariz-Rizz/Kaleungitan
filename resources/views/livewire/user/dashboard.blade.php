<div>

    @if ($successMessage)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg flex items-center justify-between">
            <span>{{ $successMessage }}</span>
            <button @click="show = false" class="text-green-700">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
    @endif

    {{-- Welcome Header --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-on-surface">Halo, {{ auth()->user()->name }} 👋</h1>
        <p class="text-sm text-on-surface-variant mt-1">Kelola laporan dan klaim barang kamu di sini.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-surface-container-lowest p-5 rounded-xl shadow-sm border border-outline-variant">
            <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total Laporan</p>
            <p class="text-3xl font-bold text-primary mt-1">{{ $totalReports }}</p>
        </div>
        <div class="bg-amber-50 p-5 rounded-xl shadow-sm border border-amber-100">
            <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Menunggu Verifikasi</p>
            <p class="text-3xl font-bold text-amber-700 mt-1">{{ $pendingReports }}</p>
        </div>
        <div class="bg-primary/5 p-5 rounded-xl shadow-sm border border-primary/20">
            <p class="text-xs font-semibold text-primary uppercase tracking-wider">Klaim Pending</p>
            <p class="text-3xl font-bold text-primary mt-1">{{ $pendingClaims }}</p>
        </div>
        <div class="bg-green-50 p-5 rounded-xl shadow-sm border border-green-100">
            <p class="text-xs font-semibold text-green-700 uppercase tracking-wider">Selesai</p>
            <p class="text-3xl font-bold text-green-700 mt-1">{{ $resolvedReports }}</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex items-center gap-6 border-b border-outline-variant mb-6">
        <button wire:click="setTab('reports')"
            class="pb-3 text-sm font-semibold border-b-2 transition-colors
                    {{ $activeTab === 'reports' ? 'border-primary text-primary' : 'border-transparent text-on-surface-variant hover:text-on-surface' }}">
            My Reports
        </button>
        <button wire:click="setTab('claims')"
            class="pb-3 text-sm font-semibold border-b-2 transition-colors
                    {{ $activeTab === 'claims' ? 'border-primary text-primary' : 'border-transparent text-on-surface-variant hover:text-on-surface' }}">
            My Claims
        </button>
    </div>

    {{-- Tab: My Reports --}}
    @if ($activeTab === 'reports')
        @if ($myReports->isEmpty())
            <div
                class="bg-surface-container-low border border-dashed border-outline-variant rounded-xl p-12 text-center">
                <span class="material-symbols-outlined text-5xl text-outline-variant mb-3">inventory_2</span>
                <h3 class="text-lg font-semibold">Belum Ada Laporan</h3>
                <p class="text-sm text-on-surface-variant mt-1">Kamu belum melaporkan barang hilang/temuan apapun.</p>
                <a href="{{ route('report.lost') }}"
                    class="mt-4 inline-block bg-primary text-on-primary px-5 py-2 rounded-full text-sm font-semibold">
                    Lapor Barang Sekarang
                </a>
            </div>
        @else
            @php
                $statusBadge = [
                    'pending' => ['bg-amber-100 text-amber-700', 'Pending'],
                    'verified' => ['bg-blue-100 text-blue-700', 'Verified'],
                    'claimed' => ['bg-green-100 text-green-700', 'Claimed'],
                    'resolved' => ['bg-green-100 text-green-700', 'Resolved'],
                    'rejected' => ['bg-red-100 text-red-700', 'Rejected'],
                ];
                $typeIcon = ['hilang' => 'search', 'temuan' => 'inventory_2'];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($myReports as $item)
                    <div
                        class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden flex flex-col">
                        <div
                            class="relative h-36 bg-surface-container flex items-center justify-center overflow-hidden">
                            @if ($item->photo)
                                <img src="{{ asset('storage/' . $item->photo) }}" class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-5xl text-on-surface-variant/40">
                                    {{ $typeIcon[$item->type] ?? 'inventory_2' }}
                                </span>
                            @endif
                            <span
                                class="absolute top-3 right-3 px-3 py-1 rounded-full text-[11px] font-bold uppercase {{ $statusBadge[$item->status][0] ?? '' }}">
                                {{ $statusBadge[$item->status][1] ?? $item->status }}
                            </span>
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-semibold text-sm mb-1">{{ $item->name }}</h3>
                            <div class="flex items-center gap-1.5 text-on-surface-variant text-xs mb-1">
                                <span class="material-symbols-outlined text-sm">location_on</span>
                                {{ $item->location }}
                            </div>
                            <div class="flex items-center gap-1.5 text-on-surface-variant text-xs mb-4">
                                <span class="material-symbols-outlined text-sm">schedule</span>
                                {{ $item->date->translatedFormat('d M Y') }}
                            </div>
                            <div class="mt-auto flex items-center gap-2">
                                <a href="{{ route('items.show', $item->id) }}"
                                    class="flex-1 text-center bg-surface-container-low text-on-surface-variant py-2 rounded-lg text-xs font-semibold hover:bg-surface-container">
                                    Lihat Detail
                                </a>
                                @if ($item->status === 'pending')
                                    <a href="{{ route('report.edit', $item->id) }}"
                                        title="Edit Laporan"
                                        class="flex items-center justify-center w-9 h-9 flex-shrink-0 bg-surface-container-low text-on-surface-variant rounded-lg hover:bg-surface-container">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <button type="button"
                                        wire:click="deleteReport({{ $item->id }})"
                                        wire:confirm="Yakin ingin menghapus laporan ini?"
                                        title="Hapus Laporan"
                                        class="flex items-center justify-center w-9 h-9 flex-shrink-0 bg-error-container/20 text-error rounded-lg hover:bg-error-container/40">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    {{-- Tab: My Claims --}}
    @if ($activeTab === 'claims')
        @if ($myClaims->isEmpty())
            <div
                class="bg-surface-container-low border border-dashed border-outline-variant rounded-xl p-12 text-center">
                <span class="material-symbols-outlined text-5xl text-outline-variant mb-3">search</span>
                <h3 class="text-lg font-semibold">Belum Ada Klaim</h3>
                <p class="text-sm text-on-surface-variant mt-1">Kamu belum mengajukan klaim atas barang temuan apapun.
                </p>
                <a href="{{ route('browse.items') }}"
                    class="mt-4 inline-block bg-primary text-on-primary px-5 py-2 rounded-full text-sm font-semibold">
                    Cari Barang Temuan
                </a>
            </div>
        @else
            @php
                $claimBadge = [
                    'pending' => 'bg-amber-100 text-amber-700',
                    'approved' => 'bg-green-100 text-green-700',
                    'rejected' => 'bg-red-100 text-red-700',
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($myClaims as $claim)
                    <a href="{{ route('items.show', $claim->item_id) }}"
                        class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-4 block hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-semibold text-sm">{{ $claim->item->name ?? '-' }}</h3>
                            <span
                                class="px-3 py-1 rounded-full text-[11px] font-bold uppercase {{ $claimBadge[$claim->status] ?? '' }}">
                                {{ $claim->status }}
                            </span>
                        </div>
                        <p class="text-xs text-on-surface-variant mb-3">{{ $claim->item->category->name ?? '-' }}</p>
                        <p class="text-xs text-on-surface-variant">
                            Diajukan {{ $claim->created_at->translatedFormat('d M Y') }}
                        </p>
                    </a>
                @endforeach
            </div>
        @endif
    @endif

</div>
