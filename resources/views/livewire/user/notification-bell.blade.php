<div class="relative" x-data="{ open: false }" wire:poll.15s>
    <button @click="open = !open" class="relative p-2 rounded-full hover:bg-surface-container-low text-on-surface-variant">
        <span class="material-symbols-outlined">notifications</span>
        @if ($unreadCount > 0)
            <span class="absolute top-0 right-0 w-4 h-4 bg-error text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="open" @click.outside="open = false" x-cloak
         class="absolute right-0 mt-2 w-80 bg-surface-container-lowest rounded-lg shadow-lg border border-outline-variant py-2 text-sm z-50 max-h-96 overflow-y-auto">

        <div class="flex items-center justify-between px-4 py-2 border-b border-outline-variant">
            <p class="font-semibold text-sm">Notifikasi</p>
            @if ($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-primary hover:underline">
                    Tandai semua dibaca
                </button>
            @endif
        </div>

        @forelse ($notifications as $notif)
            <button wire:click="markAsRead('{{ $notif->id }}')"
                    class="w-full text-left px-4 py-3 hover:bg-surface-container-low border-b border-outline-variant last:border-b-0 flex gap-2
                        {{ is_null($notif->read_at) ? 'bg-primary/5' : '' }}">
                @if (is_null($notif->read_at))
                    <span class="w-2 h-2 rounded-full bg-primary mt-1.5 flex-shrink-0"></span>
                @else
                    <span class="w-2 h-2 flex-shrink-0"></span>
                @endif
                <div>
                    <p class="text-xs text-on-surface leading-relaxed">{{ $notif->data['message'] ?? '-' }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
            </button>
        @empty
            <div class="px-4 py-8 text-center">
                <span class="material-symbols-outlined text-3xl text-outline-variant mb-1">notifications_off</span>
                <p class="text-xs text-on-surface-variant">Belum ada notifikasi.</p>
            </div>
        @endforelse

    </div>
</div>
