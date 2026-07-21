<div>
    @if ($this->reports->isEmpty())
        <div class="bg-surface-container-low text-on-surface-variant text-sm text-center py-3 rounded-lg">
            Belum ada laporan penemuan untuk barang ini.
        </div>
    @else
        <div class="flex flex-col gap-3">
            <p class="text-sm font-semibold text-on-surface">
                {{ $this->reports->count() }} laporan penemuan menunggu verifikasimu
            </p>

            @foreach ($this->reports as $report)
                <div class="bg-surface-container-low rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div
                            class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container text-xs font-semibold shrink-0">
                            {{ strtoupper(substr($report->user->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium leading-tight">{{ $report->user->name ?? '-' }}</p>
                            <p class="text-[11px] text-on-surface-variant">{{ $report->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <p class="text-sm text-on-surface-variant leading-relaxed mb-3">{{ $report->description }}</p>

                    <div class="flex gap-2">
                        <button wire:click="confirmFound({{ $report->id }})"
                            wire:confirm="Konfirmasi barang ini sudah ditemukan? Laporan lain yang pending akan otomatis ditolak."
                            wire:loading.attr="disabled"
                            class="flex-1 py-2 bg-primary text-on-primary rounded-lg text-xs font-semibold hover:opacity-90 transition-opacity flex items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-sm">check_circle</span>
                            Ya, ini barangku
                        </button>
                        <button wire:click="rejectReport({{ $report->id }})"
                            wire:confirm="Tandai laporan ini bukan barang yang dimaksud?"
                            wire:loading.attr="disabled"
                            class="flex-1 py-2 bg-surface-container-lowest border border-outline-variant text-on-surface-variant rounded-lg text-xs font-semibold hover:bg-surface-container transition-colors flex items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-sm">cancel</span>
                            Bukan barang ini
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
