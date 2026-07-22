<section>
    <header class="flex items-start gap-3">
        <div class="w-10 h-10 rounded-lg bg-error/10 flex items-center justify-center text-error shrink-0">
            <span class="material-symbols-outlined text-xl">warning</span>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-error">
                {{ __('Hapus Akun') }}
            </h2>
            <p class="mt-0.5 text-sm text-on-surface-variant">
                {{ __('Setelah dihapus, seluruh data dan laporan kamu akan hilang secara permanen dan tidak bisa dikembalikan.') }}
            </p>
        </div>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="mt-5 bg-error text-white px-5 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">
        {{ __('Hapus Akun Saya') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-start gap-3 mb-1">
                <div class="w-10 h-10 rounded-lg bg-error/10 flex items-center justify-center text-error shrink-0">
                    <span class="material-symbols-outlined text-xl">warning</span>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-on-surface">
                        {{ __('Yakin ingin menghapus akun ini?') }}
                    </h2>
                    <p class="mt-1 text-sm text-on-surface-variant">
                        {{ __('Tindakan ini tidak bisa dibatalkan. Masukkan password kamu untuk konfirmasi.') }}
                    </p>
                </div>
            </div>

            <div class="mt-5">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input id="password" name="password" type="password"
                    class="mt-1 block w-full !bg-surface-container-low !border-none !rounded-lg !text-sm focus:!ring-2 focus:!ring-error"
                    placeholder="{{ __('Password') }}" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2 rounded-lg text-sm font-semibold border border-outline-variant text-on-surface-variant hover:bg-surface-container-low transition-colors">
                    {{ __('Batal') }}
                </button>

                <button type="submit"
                    class="bg-error text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">
                    {{ __('Ya, Hapus Akun') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
