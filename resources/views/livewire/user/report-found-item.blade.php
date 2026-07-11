<div>

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-on-surface mb-1">Report a Found Item</h1>
        <p class="text-on-surface-variant">Help a fellow student by documenting an item you've located on campus.</p>
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

    <form wire:submit="submit" class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Main form column --}}
        <div class="lg:col-span-8">
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-6 md:p-8 space-y-6">

                {{-- General Details --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">info</span>
                        <h2 class="font-semibold text-lg text-on-surface">General Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wide block"
                                for="name">Item Name</label>
                            <input wire:model="name" id="name" type="text" placeholder="e.g., Blue HydroFlask"
                                class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder:text-on-surface-variant/60">
                            @error('name')
                                <p class="text-error text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wide block"
                                for="category_id">Category</label>
                            <select wire:model="category_id" id="category_id"
                                class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all cursor-pointer">
                                <option value="" disabled selected>Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-error text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wide block"
                            for="description">Description</label>
                        <textarea wire:model="description" id="description" rows="4"
                            placeholder="Mention specific identifiers (scratches, stickers, brand names)..."
                            class="w-full p-4 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all resize-none placeholder:text-on-surface-variant/60"></textarea>
                        @error('description')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Where & When --}}
                <div class="space-y-4 pt-4 border-t border-outline-variant">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">location_on</span>
                        <h2 class="font-semibold text-lg text-on-surface">Where &amp; When</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wide block"
                                for="location">Location Found</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px]">search</span>
                                <input wire:model="location" id="location" type="text"
                                    placeholder="e.g., Library Level 3, Table 12"
                                    class="w-full h-12 pl-10 pr-4 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder:text-on-surface-variant/60">
                            </div>
                            @error('location')
                                <p class="text-error text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wide block"
                                for="date">Date Found</label>
                            <input wire:model="date" id="date" type="date" max="{{ date('Y-m-d') }}"
                                class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
                            @error('date')
                                <p class="text-error text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Current Custody --}}
                <div class="space-y-3 pt-4 border-t border-outline-variant">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">security</span>
                        <h2 class="font-semibold text-lg text-on-surface">Current Custody</h2>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wide block"
                            for="custody">Where Is The Item Now?</label>
                        <input wire:model="custody" id="custody" type="text"
                            placeholder="e.g., Campus Security Hub / Front Desk"
                            class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder:text-on-surface-variant/60">
                        <p class="text-sm text-on-surface-variant">We recommend dropping items off at designated
                            security posts for verification.</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('dashboard') }}"
                        class="px-6 h-12 flex items-center rounded-lg font-semibold text-primary hover:bg-surface-container-high transition-colors">
                        Save Draft
                    </a>
                    <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                        class="px-8 h-12 rounded-lg bg-primary text-on-primary font-semibold shadow-sm hover:shadow-lg transition-all active:scale-95 disabled:opacity-60">
                        <span wire:loading.remove wire:target="submit">Submit Report</span>
                        <span wire:loading wire:target="submit">Submitting...</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="lg:col-span-4 space-y-6">

            {{-- Photo upload card --}}
            <div
                class="bg-surface-container-lowest rounded-xl shadow-sm border border-dashed border-outline-variant p-6">
                @if ($photo)
                    <div class="relative rounded-lg overflow-hidden h-40 group">
                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                        <button type="button" wire:click="$set('photo', null)"
                            class="absolute top-2 right-2 bg-black/60 text-white rounded-full w-7 h-7 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                        </button>
                    </div>
                @else
                    <label for="photo"
                        class="flex flex-col items-center justify-center text-center py-4 cursor-pointer">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-primary text-3xl">add_a_photo</span>
                        </div>
                        <h3 class="font-semibold text-on-surface">Item Photo</h3>
                        <p class="text-xs text-on-surface-variant mt-2 max-w-[200px]">
                            Clear photos help the owner identify their item faster.
                        </p>
                        <span
                            class="mt-4 bg-surface-container text-on-surface px-5 py-2 rounded-full text-xs font-semibold hover:bg-surface-container-high transition-colors">
                            Upload Image
                        </span>
                        <input wire:model="photo" id="photo" type="file" accept="image/*" class="hidden">
                    </label>
                @endif

                <div wire:loading wire:target="photo" class="text-xs text-on-surface-variant mt-2 text-center">
                    Mengunggah foto...</div>
                @error('photo')
                    <p class="text-error text-xs mt-2 text-center">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tips card --}}
            <div class="bg-green-50 border border-green-100 p-6 rounded-xl">
                <div class="flex items-center gap-2 mb-4 text-green-800">
                    <span class="material-symbols-outlined">lightbulb</span>
                    <h3 class="font-semibold text-lg">Reporting Tips</h3>
                </div>
                <ul class="space-y-3">
                    <li class="flex gap-2 text-sm text-green-800">
                        <span class="material-symbols-outlined text-sm shrink-0">check_circle</span>
                        Don't post personal ID numbers or private info.
                    </li>
                    <li class="flex gap-2 text-sm text-green-800">
                        <span class="material-symbols-outlined text-sm shrink-0">check_circle</span>
                        Describe unique features like stickers or damage.
                    </li>
                    <li class="flex gap-2 text-sm text-green-800">
                        <span class="material-symbols-outlined text-sm shrink-0">check_circle</span>
                        Keep high-value items (laptops, jewelry) at the security office.
                    </li>
                </ul>
            </div>

            {{-- Blank map placeholder (visual only, sesuai desain asli) --}}
            <div
                class="rounded-xl h-48 border border-outline-variant bg-surface-container-low flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">map</span>
            </div>
        </aside>
    </form>

</div>
