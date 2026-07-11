<x-guest-layout>

    <div class="flex justify-center items-center gap-2 mb-1">
        <h2 class="text-xl font-semibold text-on-surface text-center">
            Admin Login
        </h2>
    </div>
    <p class="text-sm text-on-surface-variant mb-6 text-center">Khusus untuk administrator sistem Kaleungitan.</p>

    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-xs font-medium text-on-surface-variant mb-1">Email Admin</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username"
                class="w-full px-3 py-2 bg-surface-container-low border-none rounded-lg text-sm focus:ring-2 focus:ring-primary">
            @error('email')
                <span class="text-error text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-medium text-on-surface-variant mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-3 py-2 bg-surface-container-low border-none rounded-lg text-sm focus:ring-2 focus:ring-primary">
            @error('password')
                <span class="text-error text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center">
            <label for="remember_me" class="flex items-center gap-2 text-sm text-on-surface-variant">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-outline-variant text-primary focus:ring-primary">
                Ingat saya
            </label>
        </div>

        <button type="submit"
            class="w-full bg-primary text-on-primary py-2.5 rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">
            Masuk sebagai Admin
        </button>

        <p class="text-center text-sm text-on-surface-variant">
            Bukan admin?
            <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">Login sebagai pengguna</a>
        </p>
    </form>

</x-guest-layout>
