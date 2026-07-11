<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 text-center md:text-left">
        <h1 class="text-2xl md:text-3xl font-bold text-on-surface mb-1">Report a Lost Item</h1>
        <p class="text-on-surface-variant">Provide as much detail as possible to help the campus community return your
            item.</p>
        <p class="text-sm mt-2">
            <span class="text-on-surface-variant">Found something instead?</span>
            <a href="{{ route('report-item.found') }}" class="text-primary font-semibold hover:underline">Report a found
                item →</a>
        </p>
    </div>

    {{-- Success message --}}
    @if ($submitted)
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <div>
                <p class="font-semibold">Laporan berhasil dikirim!</p>
                <p class="text-sm">Laporan kamu akan diverifikasi admin sebelum tampil di Browse Items.</p>
            </div>
        </div>
    @endif

    <form wire:submit="submit"
        class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_12px_rgba(0,0,0,0.05)] p-6 md:p-10 space-y-6">

        {{-- Item Name & Category --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-1.5">
                <label class="font-semibold text-on-surface block" for="name">Item Name</label>
                <input wire:model="name" id="name" type="text" placeholder="e.g., Blue HydroFlask"
                    class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder:text-on-surface-variant/60">
                @error('name')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="space-y-1.5">
                <label class="font-semibold text-on-surface block" for="category_id">Category</label>
                <select wire:model="category_id" id="category_id"
                    class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all appearance-none cursor-pointer">
                    <option value="" disabled selected>Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Description --}}
        <div class="space-y-1.5">
            <label class="font-semibold text-on-surface block" for="description">Description</label>
            <textarea wire:model="description" id="description" rows="4"
                placeholder="Describe unique markings, stickers, or brand details..."
                class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all resize-none placeholder:text-on-surface-variant/60"></textarea>
            @error('description')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Location & Date --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-1.5">
                <label class="font-semibold text-on-surface block" for="location">Last Seen Location</label>
                <div class="relative">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px]">location_on</span>
                    <input wire:model="location" id="location" type="text"
                        placeholder="e.g., Main Library, 2nd Floor"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder:text-on-surface-variant/60">
                </div>
                @error('location')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="space-y-1.5">
                <label class="font-semibold text-on-surface block" for="date">Approximate Date Lost</label>
                <input wire:model="date" id="date" type="date" max="{{ date('Y-m-d') }}"
                    class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
                @error('date')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Photo Upload --}}
        <div class="space-y-1.5">
            <label class="font-semibold text-on-surface block">Item Photo (Optional)</label>

            @if ($photo)
                <div class="relative w-full h-56 rounded-xl overflow-hidden border border-outline-variant">
                    <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                    <button type="button" wire:click="$set('photo', null)"
                        class="absolute top-2 right-2 bg-black/60 text-white rounded-full w-7 h-7 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </button>
                </div>
            @else
                <label for="photo"
                    class="border-2 border-dashed border-outline-variant rounded-xl p-8 flex flex-col items-center justify-center bg-surface-container-lowest hover:bg-surface-container-low transition-colors cursor-pointer group">
                    <div
                        class="w-16 h-16 rounded-full bg-primary-container/10 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-primary text-4xl">cloud_upload</span>
                    </div>
                    <p class="text-on-surface text-center">Click or drag and drop to upload</p>
                    <p class="text-sm text-on-surface-variant text-center mt-1">PNG, JPG up to 2MB</p>
                    <input wire:model="photo" id="photo" type="file" accept="image/*" class="hidden">
                </label>
            @endif

            <div wire:loading wire:target="photo" class="text-xs text-on-surface-variant mt-2">Mengunggah foto...</div>
            @error('photo')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex flex-col-reverse md:flex-row gap-4 pt-2">
            <a href="{{ route('dashboard') }}"
                class="w-full md:w-1/3 py-4 rounded-lg font-semibold text-on-surface hover:bg-surface-container-high transition-colors border border-outline-variant text-center">
                Cancel
            </a>
            <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                class="w-full md:w-2/3 py-4 rounded-lg bg-primary text-on-primary font-semibold shadow-lg shadow-primary/20 hover:bg-primary-container transition-all active:scale-[0.98] disabled:opacity-60">
                <span wire:loading.remove wire:target="submit">Submit Report</span>
                <span wire:loading wire:target="submit">Submitting...</span>
            </button>
        </div>
    </form>

    {{-- Trust indicator --}}
    <div class="mt-6 flex items-center justify-center gap-2 text-on-surface-variant opacity-70">
        <span class="material-symbols-outlined text-sm">verified_user</span>
        <p class="text-sm">Your data is secured and visible only to verified campus admins.</p>
    </div>

</div>
