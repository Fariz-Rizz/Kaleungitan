<div>

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-on-surface mb-1">Browse Items</h1>
        <p class="text-sm text-on-surface-variant max-w-2xl">
            Cari barang hilang/temuan yang dilaporkan civitas akademika Universitas Muhammadiyah Bandung.
            Gunakan filter untuk mempersempit pencarian berdasarkan kategori atau tipe.
        </p>
    </div>

    {{-- Toolbar --}}
    <div
        class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 bg-surface-container-low p-4 rounded-xl mb-6">
        <div class="relative w-full md:w-96">
            <span
                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="Cari nama atau deskripsi barang..."
                class="w-full pl-10 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-full text-sm focus:ring-2 focus:ring-primary outline-none">
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <select wire:model.live="filterType"
                class="bg-surface-container-lowest border border-outline-variant rounded-lg text-sm px-3 py-2 outline-none focus:ring-2 focus:ring-primary">
                <option value="">Semua Tipe</option>
                <option value="hilang">Hilang</option>
                <option value="temuan">Temuan</option>
            </select>

            <select wire:model.live="filterCategory"
                class="bg-surface-container-lowest border border-outline-variant rounded-lg text-sm px-3 py-2 outline-none focus:ring-2 focus:ring-primary">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="sortBy"
                class="bg-surface-container-lowest border border-outline-variant rounded-lg text-sm px-3 py-2 outline-none focus:ring-2 focus:ring-primary">
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
            </select>
        </div>
    </div>

    {{-- Loading Indicator --}}
    <div wire:loading class="text-center text-sm text-on-surface-variant mb-4">
        Memuat data...
    </div>

    {{-- Grid --}}
    @if ($items->isEmpty())
        <div class="bg-surface-container-low border border-dashed border-outline-variant rounded-xl p-16 text-center">
            <span class="material-symbols-outlined text-5xl text-outline-variant mb-3">search_off</span>
            <h3 class="text-lg font-semibold">Tidak Ada Barang Ditemukan</h3>
            <p class="text-sm text-on-surface-variant mt-1">Coba ubah kata kunci atau filter pencarian kamu.</p>
        </div>
    @else
        @php
            $typeIcon = ['hilang' => 'search', 'temuan' => 'inventory_2'];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach ($items as $item)
                <a href="{{ route('items.show', $item->id) }}"
                    class="bg-surface-container-lowest rounded-xl overflow-hidden flex flex-col shadow-sm hover:shadow-md transition-shadow border border-outline-variant group">

                    <div
                        class="relative aspect-video bg-surface-container flex items-center justify-center overflow-hidden">
                        @if ($item->photo)
                            <img src="{{ asset('storage/' . $item->photo) }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        @else
                            <span
                                class="material-symbols-outlined text-5xl text-on-surface-variant/40 group-hover:scale-105 transition-transform">
                                {{ $typeIcon[$item->type] ?? 'inventory_2' }}
                            </span>
                        @endif

                        @if (in_array($item->status, ['claimed', 'resolved']))
                            <span
                                class="absolute top-3 left-3 px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-surface-container-high text-on-surface-variant">
                                Resolved
                            </span>
                        @elseif ($item->type === 'hilang')
                            <span
                                class="absolute top-3 left-3 px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-primary/10 text-primary">
                                Lost
                            </span>
                        @else
                            <span
                                class="absolute top-3 left-3 px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-secondary/10 text-secondary">
                                Found
                            </span>
                        @endif
                    </div>

                    <div class="p-4 flex flex-col flex-grow">
                        <div class="flex justify-between items-start mb-1 gap-2">
                            <h3 class="font-semibold text-sm text-on-surface truncate">{{ $item->name }}</h3>
                            <span
                                class="text-on-surface-variant text-[11px] whitespace-nowrap">{{ $item->date->diffForHumans() }}</span>
                        </div>
                        <p class="text-on-surface-variant text-xs mb-3 flex-grow line-clamp-2">{{ $item->description }}
                        </p>
                        <div
                            class="flex items-center gap-1.5 pt-2 border-t border-surface-container text-xs text-on-surface-variant">
                            <span class="material-symbols-outlined text-sm">location_on</span>
                            {{ $item->location }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $items->links() }}
        </div>
    @endif

</div>
