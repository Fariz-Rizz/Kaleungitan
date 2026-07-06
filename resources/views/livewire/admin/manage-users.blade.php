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
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-on-surface">Manage Users</h2>
        <p class="text-sm text-on-surface-variant">Pantau aktivitas & kelola akses pengguna.</p>
    </div>

    {{-- Search --}}
    <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm border border-outline-variant mb-6">
        <div class="relative max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
            <input type="text" wire:model.live.debounce.400ms="search"
                   placeholder="Cari nama / email..."
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
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Nama</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Email</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Role</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Laporan</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Klaim</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Bergabung</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($users as $user)
                        <tr class="hover:bg-surface-container-low/50">
                            <td class="p-4 text-sm font-medium">{{ $user->name }}</td>
                            <td class="p-4 text-sm text-on-surface-variant">{{ $user->email }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase
                                    {{ $user->role === 'admin' ? 'bg-primary/10 text-primary' : 'bg-surface-container text-on-surface-variant' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-on-surface-variant">{{ $user->items_count }}</td>
                            <td class="p-4 text-sm text-on-surface-variant">{{ $user->claims_count }}</td>
                            <td class="p-4 text-sm text-on-surface-variant">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase
                                    {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $user->is_active ? 'Active' : 'Suspended' }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                @if ($user->role !== 'admin')
                                    <button wire:click="toggleStatus({{ $user->id }})"
                                            wire:confirm="Yakin ingin {{ $user->is_active ? 'suspend' : 'aktifkan kembali' }} user ini?"
                                            class="px-3 py-1 rounded-md text-sm font-semibold
                                                {{ $user->is_active ? 'text-error hover:bg-error-container/30' : 'text-primary hover:bg-primary/5' }}">
                                        {{ $user->is_active ? 'Suspend' : 'Aktifkan' }}
                                    </button>
                                @else
                                    <span class="text-xs text-on-surface-variant italic">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-sm text-on-surface-variant">
                                Tidak ada user ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-outline-variant">
            {{ $users->links() }}
        </div>
    </div>

</div>
