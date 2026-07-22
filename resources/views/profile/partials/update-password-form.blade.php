<section>
    <header class="flex items-start gap-3 mb-6">
        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0">
            <span class="material-symbols-outlined text-xl">lock</span>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-on-surface">
                {{ __('Ubah Password') }}
            </h2>
            <p class="mt-0.5 text-sm text-on-surface-variant">
                {{ __('Gunakan password yang panjang dan unik agar akun kamu tetap aman.') }}
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Password Saat Ini')" class="!text-on-surface-variant" />
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class="mt-1 block w-full !bg-surface-container-low !border-none !rounded-lg !text-sm focus:!ring-2 focus:!ring-primary"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <x-input-label for="update_password_password" :value="__('Password Baru')" class="!text-on-surface-variant" />
                <x-text-input id="update_password_password" name="password" type="password"
                    class="mt-1 block w-full !bg-surface-container-low !border-none !rounded-lg !text-sm focus:!ring-2 focus:!ring-primary"
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password')"
                    class="!text-on-surface-variant" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    class="mt-1 block w-full !bg-surface-container-low !border-none !rounded-lg !text-sm focus:!ring-2 focus:!ring-primary"
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-1">
            <button type="submit"
                class="bg-primary text-on-primary px-5 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-700 font-medium flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">check_circle</span>
                    {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
