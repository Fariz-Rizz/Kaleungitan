<div class="max-w-2xl mx-auto">

    <a href="{{ route('items.show', $item->id) }}"
        class="inline-flex items-center gap-1 text-sm text-on-surface-variant hover:text-primary mb-6">
        <span class="material-symbols-outlined text-lg">arrow_back</span>
        Kembali ke Detail Barang
    </a>

    <div class="mb-6">
        <p class="text-sm font-semibold text-secondary mb-1">{{ $item->name }}</p>
        <h1 class="text-2xl md:text-3xl font-bold text-on-surface">Ajukan Klaim Kepemilikan</h1>
        <p class="text-sm text-on-surface-variant mt-1">
            Jelaskan ciri khusus barang ini yang hanya diketahui pemilik asli — admin akan membandingkan
            dengan deskripsi asli dari pelapor sebelum menyetujui klaim kamu.
        </p>
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-6 md:p-8">

        <div class="bg-surface-container-low rounded-lg p-4 mb-5">
            <p class="text-xs font-semibold text-on-surface-variant uppercase mb-1">Kategori</p>
            <p class="text-sm">{{ $item->category->name ?? '-' }}</p>
        </div>

        <div class="mb-5">
            <label class="block text-sm font-semibold text-on-surface mb-1.5">Deskripsi Bukti Kepemilikan</label>
            <textarea wire:model="description" rows="5"
                placeholder="Contoh: Di dalam dompet ada kartu mahasiswa atas nama saya, dan ada goresan kecil di bagian sudut kiri..."
                class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none"></textarea>
            @error('description')
                <span class="text-error text-xs">{{ $message }}</span>
            @enderror
        </div>

        <button wire:click="submit" wire:loading.attr="disabled"
            class="w-full py-3.5 bg-primary text-on-primary rounded-lg font-semibold shadow-sm hover:opacity-90 transition-opacity disabled:opacity-50">
            <span wire:loading.remove>Ajukan Klaim</span>
            <span wire:loading>Mengirim...</span>
        </button>

    </div>

</div>
