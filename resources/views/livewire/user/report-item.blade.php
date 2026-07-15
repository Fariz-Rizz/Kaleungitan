<div class="max-w-2xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-on-surface">
            @if ($item)
                Edit Laporan
            @else
                {{ $type === 'hilang' ? 'Laporkan Barang Hilang' : 'Laporkan Barang Temuan' }}
            @endif
        </h1>
        <p class="text-sm text-on-surface-variant mt-1">
            @if ($item)
                Perbarui detail laporan kamu di bawah ini.
            @elseif ($type === 'hilang')
                Isi detail barang yang kamu hilangkan selengkap mungkin agar mudah ditemukan.
            @else
                Isi detail barang yang kamu temukan agar pemiliknya bisa segera dihubungi.
            @endif
        </p>
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-6 md:p-8">

        <div class="space-y-5">

            {{-- Nama Barang --}}
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1.5">Nama Barang</label>
                <input type="text" wire:model="name" placeholder="Contoh: Dompet Kulit Coklat"
                    class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none">
                @error('name')
                    <span class="text-error text-xs">{{ $message }}</span>
                @enderror
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1.5">Kategori</label>
                <select wire:model="category_id"
                    class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-error text-xs">{{ $message }}</span>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1.5">Deskripsi</label>
                <textarea wire:model="description" rows="4"
                    placeholder="Ceritakan ciri khusus, merk, warna, atau tanda pengenal lain..."
                    class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none"></textarea>
                @error('description')
                    <span class="text-error text-xs">{{ $message }}</span>
                @enderror
            </div>

            {{-- Lokasi --}}
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1.5">
                    {{ $type === 'hilang' ? 'Lokasi Terakhir Terlihat' : 'Lokasi Ditemukan' }}
                </label>
                <input type="text" wire:model="location" placeholder="Contoh: Perpustakaan Lantai 2"
                    class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none">
                @error('location')
                    <span class="text-error text-xs">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1.5">
                    {{ $type === 'hilang' ? 'Tanggal Hilang' : 'Tanggal Ditemukan' }}
                </label>
                <input type="date" wire:model="date"
                    class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none">
                @error('date')
                    <span class="text-error text-xs">{{ $message }}</span>
                @enderror
            </div>

            {{-- Upload Foto --}}
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1.5">Foto Barang (Opsional)</label>

                @if ($photo)
                    <div class="relative w-full h-48 rounded-lg overflow-hidden mb-3 bg-surface-container">
                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                        <button type="button" wire:click="removePhoto"
                            class="absolute top-2 right-2 bg-black/60 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-black/80">
                            <span class="material-symbols-outlined text-lg">close</span>
                        </button>
                    </div>
                @elseif ($existingPhoto)
                    <div class="relative w-full h-48 rounded-lg overflow-hidden mb-3 bg-surface-container">
                        <img src="{{ asset('storage/' . $existingPhoto) }}" class="w-full h-full object-cover">
                        <button type="button" wire:click="removePhoto"
                            class="absolute top-2 right-2 bg-black/60 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-black/80">
                            <span class="material-symbols-outlined text-lg">close</span>
                        </button>
                    </div>
                @else
                    <label
                        class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-outline-variant rounded-lg py-8 cursor-pointer hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-3xl text-on-surface-variant">add_a_photo</span>
                        <span class="text-sm text-on-surface-variant">Klik untuk upload foto (maks 2MB)</span>
                        <input type="file" wire:model="photo" accept="image/*" class="hidden">
                    </label>
                @endif

                <div wire:loading wire:target="photo" class="text-xs text-primary mt-1">Mengunggah foto...</div>
                @error('photo')
                    <span class="text-error text-xs">{{ $message }}</span>
                @enderror
            </div>

            <button wire:click="save" wire:loading.attr="disabled" wire:target="save,photo"
                class="w-full py-3.5 bg-primary text-on-primary rounded-lg font-semibold shadow-sm hover:opacity-90 transition-opacity disabled:opacity-50">
                <span wire:loading.remove wire:target="save">{{ $item ? 'Simpan Perubahan' : 'Kirim Laporan' }}</span>
                <span wire:loading wire:target="save">{{ $item ? 'Menyimpan...' : 'Mengirim...' }}</span>
            </button>

        </div>
    </div>

</div>
