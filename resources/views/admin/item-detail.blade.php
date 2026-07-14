@extends('layouts.admin')

@section('title', $item->name)

@section('content')

    <a  href="{{ url()->previous() }}"
        class="inline-flex items-center gap-1 text-sm text-on-surface-variant hover:text-primary mb-6">
        <span class="material-symbols-outlined text-lg">arrow_back</span>
        Kembali
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

            {{-- Claims for this item --}}
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-6">
                <h3 class="text-sm font-semibold mb-4">Klaim untuk Barang Ini ({{ $item->claims->count() }})</h3>

                @forelse ($item->claims as $claim)
                    <div class="flex items-start gap-3 py-3 border-b border-outline-variant last:border-b-0">
                        <div class="w-9 h-9 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-semibold text-sm flex-shrink-0">
                            {{ strtoupper(substr($claim->user->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-medium">{{ $claim->user->name ?? 'Pengguna tidak diketahui' }}</p>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    {{ match($claim->status) {
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-error-container text-on-error-container',
                                        default => 'bg-amber-100 text-amber-700',
                                    } }}">
                                    {{ $claim->status }}
                                </span>
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1 leading-relaxed">{{ $claim->description }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-1">{{ $claim->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-on-surface-variant text-center py-4">Belum ada klaim untuk barang ini.</p>
                @endforelse

                @if ($item->claims->where('status', 'pending')->count() > 0)
                    <a href="{{ route('admin.claims') }}"
                        class="mt-4 w-full py-2.5 bg-primary text-on-primary rounded-lg font-semibold text-sm shadow-sm hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-lg">assignment_turned_in</span>
                        Kelola Klaim di Manage Claims
                    </a>
                @endif
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

                {{-- Admin quick action --}}
                @if ($item->status === 'pending')
                    <a href="{{ route('admin.reports') }}"
                        class="w-full py-3 bg-primary text-on-primary rounded-lg font-semibold shadow-sm hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">fact_check</span>
                        Verifikasi Laporan Ini
                    </a>
                @else
                    <a href="{{ route('admin.reports') }}"
                        class="w-full py-3 bg-surface-container-low text-on-surface rounded-lg font-semibold hover:bg-surface-container transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">open_in_new</span>
                        Buka di Manage Reports
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
