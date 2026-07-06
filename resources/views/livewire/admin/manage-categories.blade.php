<div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-on-surface">Manage Categories</h2>
            <p class="text-sm text-on-surface-variant">Kelola kategori barang untuk laporan hilang & temuan.</p>
        </div>
        <button wire:click="openCreate"
                class="bg-primary text-on-primary px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">add</span>
            Tambah Kategori
        </button>
    </div>

    {{-- Search --}}
    <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm border border-outline-variant mb-6">
        <div class="relative max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
            <input type="text" wire:model.live.debounce.400ms="search"
                   placeholder="Cari kategori..."
                   class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-lg w-full text-sm focus:ring-2 focus:ring-primary">
        </div>
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
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Nama Kategori</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Jumlah Barang</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($categories as $cat)
                        <tr class="hover:bg-surface-container-low/50">
                            <td class="p-4 text-sm font-medium">{{ $cat->name }}</td>
                            <td class="p-4 text-sm text-on-surface-variant">{{ $cat->items_count }} barang</td>
                            <td class="p-4 text-right">
                                <button wire:click="openEdit({{ $cat->id }})"
                                        class="text-primary hover:bg-primary/5 px-3 py-1 rounded-md text-sm font-semibold">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $cat->id }})"
                                        class="text-error hover:bg-error-container/30 px-3 py-1 rounded-md text-sm font-semibold">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-sm text-on-surface-variant">
                                Belum ada kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-outline-variant">
            {{ $categories->links() }}
        </div>
    </div>

    {{-- Modal Tambah/Edit --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" wire:click.self="closeModal">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-6">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">{{ $editingId ? 'Edit Kategori' : 'Tambah Kategori' }}</h3>
                    <button wire:click="closeModal" class="text-on-surface-variant hover:text-on-surface">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Nama Kategori</label>
                    <input type="text" wire:model="name"
                           class="w-full px-3 py-2 bg-surface-container-low border-none rounded-lg text-sm focus:ring-2 focus:ring-primary"
                           placeholder="Misal: Elektronik">
                    @error('name') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-2 justify-end">
                    <button wire:click="closeModal"
                            class="px-4 py-2 rounded-lg text-sm bg-surface-container-low text-on-surface-variant font-semibold">
                        Batal
                    </button>
                    <button wire:click="save"
                            class="px-4 py-2 rounded-lg text-sm bg-primary text-on-primary font-semibold">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Konfirmasi Hapus --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" wire:click.self="cancelDelete">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-6">
                <h3 class="text-lg font-semibold mb-2">Hapus Kategori?</h3>
                <p class="text-sm text-on-surface-variant mb-4">
                    Tindakan ini tidak bisa dibatalkan. Kategori yang masih dipakai laporan tidak akan bisa dihapus.
                </p>
                <div class="flex gap-2 justify-end">
                    <button wire:click="cancelDelete"
                            class="px-4 py-2 rounded-lg text-sm bg-surface-container-low text-on-surface-variant font-semibold">
                        Batal
                    </button>
                    <button wire:click="delete"
                            class="px-4 py-2 rounded-lg text-sm bg-error text-on-error font-semibold">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
