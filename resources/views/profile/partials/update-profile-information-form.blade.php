<section>
    <header class="flex items-start gap-3 mb-6">
        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0">
            <span class="material-symbols-outlined text-xl">person</span>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-on-surface">
                {{ __('Informasi Profil') }}
            </h2>
            <p class="mt-0.5 text-sm text-on-surface-variant">
                {{ __('Perbarui nama dan alamat email akun kamu.') }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <x-input-label for="name" :value="__('Nama')" class="!text-on-surface-variant" />
                <x-text-input id="name" name="name" type="text"
                    class="mt-1 block w-full !bg-surface-container-low !border-none !rounded-lg !text-sm focus:!ring-2 focus:!ring-primary"
                    :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="!text-on-surface-variant" />
                <x-text-input id="email" name="email" type="email"
                    class="mt-1 block w-full !bg-surface-container-low !border-none !rounded-lg !text-sm focus:!ring-2 focus:!ring-primary"
                    :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div class="bg-amber-50 border border-amber-100 rounded-lg px-4 py-3">
                <p class="text-sm text-amber-800">
                    {{ __('Alamat email kamu belum diverifikasi.') }}

                    <button form="send-verification"
                        class="underline font-semibold hover:opacity-80 focus:outline-none">
                        {{ __('Kirim ulang email verifikasi.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-700">
                        {{ __('Link verifikasi baru telah dikirim ke email kamu.') }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-4 pt-1">
            <button type="submit"
                class="bg-primary text-on-primary px-5 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-700 font-medium flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">check_circle</span>
                    {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
