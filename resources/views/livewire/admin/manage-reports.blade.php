<div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-on-surface">Manage Reports</h2>
            <p class="text-sm text-on-surface-variant">Kelola & verifikasi laporan barang hilang/temuan.</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm border border-outline-variant mb-6 flex flex-wrap gap-3 items-center">

        <div class="relative flex-1 min-w-[200px]">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
            <input type="text" wire:model.live.debounce.400ms="search"
                   placeholder="Cari nama barang..."
                   class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-lg w-full text-sm focus:ring-2 focus:ring-primary">
        </div>

        <select wire:model.live="filterType" class="px-3 py-2 bg-surface-container-low border-none rounded-lg text-sm">
            <option value="">Semua Tipe</option>
            <option value="hilang">Hilang</option>
            <option value="temuan">Temuan</option>
        </select>

        <select wire:model.live="filterStatus" class="px-3 py-2 bg-surface-container-low border-none rounded-lg text-sm">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="verified">Verified</option>
            <option value="claimed">Claimed</option>
            <option value="resolved">Resolved</option>
            <option value="rejected">Rejected</option>
        </select>

        <select wire:model.live="filterCategory" class="px-3 py-2 bg-surface-container-low border-none rounded-lg text-sm">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden relative">

        {{-- Loading indicator --}}
        <div wire:loading class="absolute inset-0 bg-white/60 z-10 flex items-center justify-center">
            <span class="material-symbols-outlined animate-spin text-primary text-3xl">progress_activity</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Barang</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Tipe</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Kategori</th>
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
                    @endphp

                    @forelse ($items as $item)
                        <tr class="hover:bg-surface-container-low/50">
                            <td class="p-4 text-sm font-medium">{{ $item->name }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase {{ $typeColor[$item->type] }}">
                                    {{ $item->type }}
                                </span>
                            </td>
                            <td class="p-4 text-sm">{{ $item->category->name ?? '-' }}</td>
                            <td class="p-4 text-sm">{{ $item->user->name ?? '-' }}</td>
                            <td class="p-4 text-sm text-on-surface-variant">{{ $item->date->translatedFormat('d M Y') }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase {{ $statusColor[$item->status] }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button wire:click="openDetail({{ $item->id }})"
                                        class="text-primary hover:bg-primary/5 px-3 py-1 rounded-md text-sm font-semibold">
                                    Lihat
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-sm text-on-surface-variant">
                                Tidak ada laporan ditemukan.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-outline-variant">
            {{ $items->links() }}
        </div>
    </div>

    {{-- Modal Detail --}}
    @if ($showModal && $selectedItem)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" wire:click.self="closeModal">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Detail Laporan</h3>
                    <button wire:click="closeModal" class="text-on-surface-variant hover:text-on-surface">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="space-y-3 text-sm mb-4">
                    <div><span class="text-on-surface-variant">Nama Barang:</span> <span class="font-medium">{{ $selectedItem->name }}</span></div>
                    <div><span class="text-on-surface-variant">Tipe:</span> {{ $selectedItem->type }}</div>
                    <div><span class="text-on-surface-variant">Kategori:</span> {{ $selectedItem->category->name ?? '-' }}</div>
                    <div><span class="text-on-surface-variant">Deskripsi:</span> {{ $selectedItem->description }}</div>
                    <div><span class="text-on-surface-variant">Lokasi:</span> {{ $selectedItem->location }}</div>
                    <div><span class="text-on-surface-variant">Tanggal:</span> {{ $selectedItem->date->translatedFormat('d M Y') }}</div>
                    <div><span class="text-on-surface-variant">Pelapor:</span> {{ $selectedItem->user->name ?? '-' }}</div>
                    <div><span class="text-on-surface-variant">Status saat ini:</span> {{ $selectedItem->status }}</div>
                </div>

                @if ($selectedItem->status === 'pending')
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-on-surface-variant mb-1">Alasan Penolakan (isi hanya jika reject)</label>
                        <textarea wire:model="rejectReason" rows="2"
                                  class="w-full px-3 py-2 bg-surface-container-low border-none rounded-lg text-sm focus:ring-2 focus:ring-primary"></textarea>
                        @error('rejectReason') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-2 justify-end">
                        <button wire:click="reject({{ $selectedItem->id }})"
                                class="px-4 py-2 rounded-lg text-sm bg-error-container text-error font-semibold">
                            Reject
                        </button>
                        <button wire:click="approve({{ $selectedItem->id }})"
                                class="px-4 py-2 rounded-lg text-sm bg-primary text-on-primary font-semibold">
                            Approve
                        </button>
                    </div>
                @else
                    <div class="flex gap-2 justify-end">
                        <button wire:click="archive({{ $selectedItem->id }})"
                                class="px-4 py-2 rounded-lg text-sm bg-surface-container-low text-on-surface-variant font-semibold">
                            Tandai Selesai
                        </button>
                    </div>
                @endif

            </div>
        </div>
    @endif

</div>
