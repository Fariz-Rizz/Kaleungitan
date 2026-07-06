<div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-on-surface">Manage Claims</h2>
        <p class="text-sm text-on-surface-variant">Verifikasi pengajuan klaim barang temuan.</p>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm border border-outline-variant mb-6 flex flex-wrap gap-3 items-center">

        <div class="relative flex-1 min-w-[200px]">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
            <input type="text" wire:model.live.debounce.400ms="search"
                   placeholder="Cari nama barang / pengklaim..."
                   class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-lg w-full text-sm focus:ring-2 focus:ring-primary">
        </div>

        <select wire:model.live="filterStatus" class="px-3 py-2 bg-surface-container-low border-none rounded-lg text-sm">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden relative">

        <div wire:loading class="absolute inset-0 bg-white/60 z-10 flex items-center justify-center">
            <span class="material-symbols-outlined animate-spin text-primary text-3xl">progress_activity</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Barang Diklaim</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Pengklaim</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Tanggal Ajuan</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">

                    @php
                        $statusColor = [
                            'pending' => 'bg-amber-100 text-amber-700',
                            'approved' => 'bg-green-100 text-green-700',
                            'rejected' => 'bg-red-100 text-red-700',
                        ];
                    @endphp

                    @forelse ($claims as $claim)
                        <tr class="hover:bg-surface-container-low/50">
                            <td class="p-4">
                                <p class="text-sm font-medium">{{ $claim->item->name ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $claim->item->category->name ?? '-' }}</p>
                            </td>
                            <td class="p-4 text-sm">{{ $claim->user->name ?? '-' }}</td>
                            <td class="p-4 text-sm text-on-surface-variant">{{ $claim->created_at->translatedFormat('d M Y') }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase {{ $statusColor[$claim->status] }}">
                                    {{ $claim->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button wire:click="openDetail({{ $claim->id }})"
                                        class="text-primary hover:bg-primary/5 px-3 py-1 rounded-md text-sm font-semibold">
                                    Lihat
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-sm text-on-surface-variant">
                                Tidak ada klaim ditemukan.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-outline-variant">
            {{ $claims->links() }}
        </div>
    </div>

    {{-- Modal Detail --}}
    @if ($showModal && $selectedClaim)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" wire:click.self="closeModal">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Verifikasi Klaim</h3>
                    <button wire:click="closeModal" class="text-on-surface-variant hover:text-on-surface">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <p class="text-sm text-on-surface-variant mb-4">
                    Bandingkan deskripsi asli barang temuan dengan deskripsi yang diberikan pengklaim,
                    untuk menilai apakah pengklaim benar-benar pemilik sah barang ini.
                </p>

                {{-- Side by side comparison --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div class="bg-surface-container-low rounded-lg p-4">
                        <p class="text-xs font-semibold text-on-surface-variant uppercase mb-2">Deskripsi Asli (dari Pelapor Temuan)</p>
                        <p class="text-sm font-medium mb-1">{{ $selectedClaim->item->name ?? '-' }}</p>
                        <p class="text-sm text-on-surface-variant">{{ $selectedClaim->item->description ?? '-' }}</p>
                        <p class="text-xs text-on-surface-variant mt-2">Lokasi: {{ $selectedClaim->item->location ?? '-' }}</p>
                        <p class="text-xs text-on-surface-variant">Dilaporkan oleh: {{ $selectedClaim->item->user->name ?? '-' }}</p>
                    </div>

                    <div class="bg-primary/5 rounded-lg p-4 border border-primary/20">
                        <p class="text-xs font-semibold text-primary uppercase mb-2">Deskripsi dari Pengklaim</p>
                        <p class="text-sm font-medium mb-1">{{ $selectedClaim->user->name ?? '-' }}</p>
                        <p class="text-sm text-on-surface-variant">{{ $selectedClaim->description }}</p>
                        <p class="text-xs text-on-surface-variant mt-2">
                            Diajukan: {{ $selectedClaim->created_at->translatedFormat('d M Y, H:i') }}
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <span class="text-xs text-on-surface-variant">Status saat ini:</span>
                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase
                        {{ $selectedClaim->status === 'pending' ? 'bg-amber-100 text-amber-700' : ($selectedClaim->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                        {{ $selectedClaim->status }}
                    </span>
                </div>

                @if ($selectedClaim->status === 'pending')
                    <div class="flex gap-2 justify-end">
                        <button wire:click="reject({{ $selectedClaim->id }})"
                                class="px-4 py-2 rounded-lg text-sm bg-error-container text-error font-semibold">
                            Reject
                        </button>
                        <button wire:click="approve({{ $selectedClaim->id }})"
                                class="px-4 py-2 rounded-lg text-sm bg-primary text-on-primary font-semibold">
                            Approve Claim & Mark as Resolved
                        </button>
                    </div>
                @else
                    <p class="text-xs text-on-surface-variant text-right italic">
                        Klaim ini sudah diproses sebelumnya, tidak ada aksi lebih lanjut.
                    </p>
                @endif

            </div>
        </div>
    @endif

</div>
