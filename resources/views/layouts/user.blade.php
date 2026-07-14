<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Kaleungitan</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-surface font-sans text-on-surface">

    {{-- Navbar --}}
    <header class="sticky top-0 z-40 bg-surface-container-lowest shadow-sm">
        <div class="max-w-7xl mx-auto px-4 md:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <img src="{{ asset('images/logo-kaleungitan.png') }}" alt="Logo Kaleungitan"
                    class="h-10 w-auto object-contain">
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ route('browse.items') }}"
                    class="{{ request()->routeIs('browse.items') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
                    Browse
                </a>
                <div class="relative" x-data="{ openReport: false }">
                    <button @click="openReport = !openReport"
                        class="text-on-surface-variant hover:text-primary transition-colors flex items-center gap-1">
                        Report
                        <span class="material-symbols-outlined text-sm">expand_more</span>
                    </button>
                    <div x-show="openReport" @click.outside="openReport = false" x-cloak
                        class="absolute left-0 mt-2 w-48 bg-surface-container-lowest rounded-lg shadow-lg border border-outline-variant py-2 text-sm z-50">
                        <a href="{{ route('report.lost') }}"
                            class="block px-4 py-2 hover:bg-surface-container-low">Lapor Barang Hilang</a>
                        <a href="{{ route('report.found') }}"
                            class="block px-4 py-2 hover:bg-surface-container-low">Lapor Barang Temuan</a>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
                    Dashboard
                </a>
            </nav>

            <div class="flex items-center gap-3" x-data="{ open: false }">
                @livewire('user.notification-bell')

                <div class="relative">
                    <button @click="open = !open"
                        class="w-9 h-9 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-semibold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </button>

                    <div x-show="open" @click.outside="open = false" x-cloak
                        class="absolute right-0 mt-2 w-48 bg-surface-container-lowest rounded-lg shadow-lg border border-outline-variant py-2 text-sm">
                        <div class="px-4 py-2 border-b border-outline-variant">
                            <p class="font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-on-surface-variant">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-surface-container-low">My
                            Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-error hover:bg-error-container/30">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Page Content --}}
    <main class="max-w-7xl mx-auto px-4 md:px-8 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-surface-container mt-12 py-6 px-4 md:px-8">
        <div
            class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-3 text-sm text-on-surface-variant">
            <div>
                <p class="font-semibold text-on-surface">Kaleungitan</p>
                <p>&copy; {{ date('Y') }} Sistem Barang Hilang & Temuan Kampus</p>
            </div>
            <div class="flex gap-4">
                <a href="#" class="hover:underline">Privacy Policy</a>
                <a href="#" class="hover:underline">Terms of Service</a>
                <a href="#" class="hover:underline">Hubungi Admin</a>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>

</html>
