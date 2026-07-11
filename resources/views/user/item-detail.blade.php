@extends('layouts.user')

@section('title', $item->name)

@section('content')

    <a href="{{ route('browse.items') }}"
        class="inline-flex items-center gap-1 text-sm text-on-surface-variant hover:text-primary mb-6">
        <span class="material-symbols-outlined text-lg">arrow_back</span>
        Kembali ke Daftar
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left: Visual --}}
        <div class="lg:col-span-7 flex flex-col gap-6">
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
                <div class="aspect-video bg-surface-container flex items-center justify-center">
                    @if ($item->photo)
                        <img src="{{ asset('storage/' . $item->photo) }}" class="w-full h-full object-cover">
                    @else
                        <span class="material-symbols-outlined text-8xl text-on-surface-variant/30">
                            {{ $item->type === 'hilang' ? 'search' : 'inventory_2' }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Info & Actions --}}
        <div class="lg:col-span-5 flex flex-col gap-6">

            {{-- Core Info --}}
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-6">
                <div class="flex justify-between items-start mb-3">
                    <span
                        class="px-3 py-1 rounded-full text-[11px] font-bold uppercase
                        {{ $item->type === 'hilang' ? 'bg-primary/10 text-primary' : 'bg-secondary/10 text-secondary' }}">
                        {{ $item->type === 'hilang' ? 'Barang Hilang' : 'Barang Temuan' }}
                    </span>
                    <span class="text-xs text-on-surface-variant">Ref:
                        #{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>

                <h1 class="text-2xl font-bold text-on-surface mb-2">{{ $item->name }}</h1>
                <p class="text-sm text-on-surface-variant leading-relaxed mb-5">{{ $item->description }}</p>

                <div class="grid grid-cols-2 gap-4 border-y border-outline-variant py-4 mb-5">
                    <div>
                        <p class="text-[11px] font-semibold text-on-surface-variant uppercase">Kategori</p>
                        <p class="text-sm font-medium">{{ $item->category->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-on-surface-variant uppercase">
                            {{ $item->type === 'hilang' ? 'Tanggal Hilang' : 'Tanggal Ditemukan' }}
                        </p>
                        <p class="text-sm font-medium">{{ $item->date->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[11px] font-semibold text-on-surface-variant uppercase">Lokasi</p>
                        <p class="text-sm font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">location_on</span>
                            {{ $item->location }}
                        </p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[11px] font-semibold text-on-surface-variant uppercase">Status</p>
                        <p class="text-sm font-medium capitalize">{{ $item->status }}</p>
                    </div>
                </div>

                {{-- Action Button --}}
                @if ($item->user_id === auth()->id())
                    <div class="bg-surface-container-low text-on-surface-variant text-sm text-center py-3 rounded-lg">
                        Ini laporan kamu sendiri.
                    </div>
                @elseif ($item->type !== 'temuan')
                    <div class="bg-surface-container-low text-on-surface-variant text-sm text-center py-3 rounded-lg">
                        Barang ini dilaporkan hilang, bukan temuan — tidak bisa diklaim.
                    </div>
                @elseif (in_array($item->status, ['claimed', 'resolved']))
                    <div class="bg-surface-container-low text-on-surface-variant text-sm text-center py-3 rounded-lg">
                        Barang ini sudah diklaim orang lain.
                    </div>
                @elseif ($existingClaim)
                    <div class="bg-amber-50 text-amber-700 text-sm text-center py-3 rounded-lg">
                        Kamu sudah mengajukan klaim untuk barang ini — status:
                        <strong>{{ $existingClaim->status }}</strong>
                    </div>
                @else
                    <a href="{{ route('items.claim', $item->id) }}"
                        class="w-full py-3 bg-primary text-on-primary rounded-lg font-semibold shadow-sm hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">verified_user</span>
                        Ajukan Klaim Barang Ini
                    </a>
                @endif
            </div>

            {{-- Reporter Info --}}
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-6">
                <h3 class="text-sm font-semibold mb-3">Informasi Pelapor</h3>
                <div class="flex items-center gap-3">
                    <div
                        class="w-11 h-11 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-semibold">
                        {{ strtoupper(substr($item->user->name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium">{{ $item->user->name ?? '-' }}</p>
                        <p class="text-xs text-on-surface-variant">
                            {{ Str::mask($item->user->email ?? '-', '•', 2, 5) }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
