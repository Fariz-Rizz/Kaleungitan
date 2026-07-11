<x-guest-layout>

    <h2 class="text-xl font-semibold text-on-surface mb-1">Buat Akun Baru</h2>
    <p class="text-sm text-on-surface-variant mb-6">Daftar untuk mulai melaporkan barang hilang/temuan.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-xs font-medium text-on-surface-variant mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                autocomplete="name"
                class="w-full px-3 py-2 bg-surface-container-low border-none rounded-lg text-sm focus:ring-2 focus:ring-primary">
            @error('name')
                <span class="text-error text-xs">{{ $message }}</span>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-medium text-on-surface-variant mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                autocomplete="username"
                class="w-full px-3 py-2 bg-surface-container-low border-none rounded-lg text-sm focus:ring-2 focus:ring-primary">
            @error('email')
                <span class="text-error text-xs">{{ $message }}</span>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-xs font-medium text-on-surface-variant mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="w-full px-3 py-2 bg-surface-container-low border-none rounded-lg text-sm focus:ring-2 focus:ring-primary">
            @error('password')
                <span class="text-error text-xs">{{ $message }}</span>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-xs font-medium text-on-surface-variant mb-1">Konfirmasi
                Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password"
                class="w-full px-3 py-2 bg-surface-container-low border-none rounded-lg text-sm focus:ring-2 focus:ring-primary">
            @error('password_confirmation')
                <span class="text-error text-xs">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-primary text-on-primary py-2.5 rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">
            Daftar
        </button>

        <p class="text-center text-sm text-on-surface-variant">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">Masuk di sini</a>
        </p>
    </form>

</x-guest-layout>
