<x-guest-layout>

    <h2 class="text-xl font-semibold text-on-surface mb-1">Masuk ke Akun</h2>
    <p class="text-sm text-on-surface-variant mb-6">Silakan login untuk melanjutkan.</p>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-medium text-on-surface-variant mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full px-3 py-2 bg-surface-container-low border-none rounded-lg text-sm focus:ring-2 focus:ring-primary">
            @error('email')
                <span class="text-error text-xs">{{ $message }}</span>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-xs font-medium text-on-surface-variant mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full px-3 py-2 bg-surface-container-low border-none rounded-lg text-sm focus:ring-2 focus:ring-primary">
            @error('password')
                <span class="text-error text-xs">{{ $message }}</span>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center gap-2 text-sm text-on-surface-variant">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-outline-variant text-primary focus:ring-primary">
                Ingat saya
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-primary hover:underline">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full bg-primary text-on-primary py-2.5 rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">
            Masuk
        </button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-on-surface-variant">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-primary font-medium hover:underline">Daftar di sini</a>
            </p>
        @endif
    </form>

</x-guest-layout>
