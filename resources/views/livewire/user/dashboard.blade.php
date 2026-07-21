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

    {{-- Stat Cards: 4 kartu, desain minimalist (1 gaya warna konsisten, dibedakan lewat aksen kecil) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-base text-on-surface-variant">inventory_2</span>
                <p class="text-xs font-medium text-on-surface-variant">Total Laporan</p>
            </div>
            <p class="text-2xl font-bold text-on-surface">{{ $totalReports }}</p>
        </div>
        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-base text-amber-600">hourglass_top</span>
                <p class="text-xs font-medium text-on-surface-variant">Menunggu Verifikasi</p>
            </div>
            <p class="text-2xl font-bold text-on-surface">{{ $pendingReports }}</p>
        </div>
        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-base text-primary">pending_actions</span>
                <p class="text-xs font-medium text-on-surface-variant">Klaim Pending</p>
            </div>
            <p class="text-2xl font-bold text-on-surface">{{ $pendingClaims }}</p>
        </div>
        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-base text-green-600">check_circle</span>
                <p class="text-xs font-medium text-on-surface-variant">Selesai</p>
            </div>
            <p class="text-2xl font-bold text-on-surface">{{ $resolvedReports }}</p>
        </div>
    </div>

    {{-- Tabs: bahasa disamakan jadi Indonesia semua --}}
    <div class="flex items-center gap-6 border-b border-outline-variant mb-6">
        <button wire:click="setTab('reports')"
            class="pb-3 text-sm font-semibold border-b-2 transition-colors
                    {{ $activeTab === 'reports' ? 'border-primary text-primary' : 'border-transparent text-on-surface-variant hover:text-on-surface' }}">
            Laporan Saya
        </button>
        <button wire:click="setTab('claims')"
            class="pb-3 text-sm font-semibold border-b-2 transition-colors
                    {{ $activeTab === 'claims' ? 'border-primary text-primary' : 'border-transparent text-on-surface-variant hover:text-on-surface' }}">
            Klaim Saya
        </button>
    </div>

    {{-- Tab: Laporan Saya --}}
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
                // Label status disamakan ke Bahasa Indonesia semua
                $statusBadge = [
                    'pending' => ['bg-amber-100 text-amber-700', 'Menunggu'],
                    'verified' => ['bg-blue-100 text-blue-700', 'Terverifikasi'],
                    'claimed' => ['bg-green-100 text-green-700', 'Diklaim'],
                    'resolved' => ['bg-green-100 text-green-700', 'Selesai'],
                    'rejected' => ['bg-red-100 text-red-700', 'Ditolak'],
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

                            {{-- Aksi disederhanakan: hanya 1 tombol utama, edit/hapus disembunyikan di menu titik tiga --}}
                            <div class="mt-auto flex items-center gap-2">
                                <a href="{{ route('items.show', $item->id) }}"
                                    class="flex-1 text-center bg-surface-container-low text-on-surface-variant py-2 rounded-lg text-xs font-semibold hover:bg-surface-container">
                                    Lihat Detail
                                </a>

                                @if ($item->status === 'pending')
                                    <div x-data="{ menuOpen: false }" class="relative flex-shrink-0">
                                        <button type="button" @click="menuOpen = !menuOpen"
                                            title="Menu Lainnya"
                                            class="flex items-center justify-center w-9 h-9 bg-surface-container-low text-on-surface-variant rounded-lg hover:bg-surface-container">
                                            <span class="material-symbols-outlined text-lg">more_vert</span>
                                        </button>

                                        <div x-show="menuOpen" @click.outside="menuOpen = false" x-cloak
                                            class="absolute right-0 bottom-11 w-40 bg-surface-container-lowest border border-outline-variant rounded-lg shadow-lg overflow-hidden z-10">
                                            <a href="{{ route('report.edit', $item->id) }}"
                                                class="flex items-center gap-2 px-4 py-2.5 text-xs font-medium hover:bg-surface-container-low">
                                                <span class="material-symbols-outlined text-base">edit</span>
                                                Edit Laporan
                                            </a>
                                            <button type="button"
                                                wire:click="deleteReport({{ $item->id }})"
                                                wire:confirm="Yakin ingin menghapus laporan ini?"
                                                class="w-full flex items-center gap-2 px-4 py-2.5 text-xs font-medium text-error hover:bg-error-container/20">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                                Hapus Laporan
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    {{-- Tab: Klaim Saya --}}
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
                    'pending' => ['bg-amber-100 text-amber-700', 'Menunggu'],
                    'approved' => ['bg-green-100 text-green-700', 'Disetujui'],
                    'rejected' => ['bg-red-100 text-red-700', 'Ditolak'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($myClaims as $claim)
                    <a href="{{ route('items.show', $claim->item_id) }}"
                        class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-4 block hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-semibold text-sm">{{ $claim->item->name ?? '-' }}</h3>
                            <span
                                class="px-3 py-1 rounded-full text-[11px] font-bold uppercase {{ $claimBadge[$claim->status][0] ?? '' }}">
                                {{ $claimBadge[$claim->status][1] ?? $claim->status }}
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
