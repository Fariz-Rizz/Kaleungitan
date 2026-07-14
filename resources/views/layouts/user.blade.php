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

<body class="bg-surface font-sans text-on-surface" x-data="{ sidebarOpen: false }">

    {{-- Overlay (mobile only, muncul saat sidebar terbuka) --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 z-40 md:hidden"></div>

    {{-- Sidebar --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed left-0 top-0 h-full w-64 z-50 bg-surface-container-lowest shadow-sm transform transition-transform duration-300 ease-in-out md:translate-x-0">
        <div class="flex flex-col h-full">
            <div class="p-6 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-primary">Kaleungitan</a>
                <button @click="sidebarOpen = false" class="md:hidden text-on-surface-variant">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <nav class="flex-1 px-2 mt-2 space-y-1">
                <a href="{{ route('browse.items') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                        {{ request()->routeIs('browse.items') ? 'bg-secondary-container text-on-secondary-container border-l-4 border-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined">search</span>
                    <span>Browse</span>
                </a>

                <div x-data="{ openReport: {{ request()->routeIs('report.*') ? 'true' : 'false' }} }">
                    <button @click="openReport = !openReport"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-colors
                            {{ request()->routeIs('report.*') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                        <span class="flex items-center gap-3">
                            <span class="material-symbols-outlined">campaign</span>
                            <span>Report</span>
                        </span>
                        <span class="material-symbols-outlined text-lg transition-transform"
                            :class="openReport ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="openReport" x-collapse class="pl-11 pr-2 py-1 space-y-1">
                        <a href="{{ route('report.lost') }}"
                            class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('report.lost') ? 'text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                            Lapor Barang Hilang
                        </a>
                        <a href="{{ route('report.found') }}"
                            class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('report.found') ? 'text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                            Lapor Barang Temuan
                        </a>
                    </div>
                </div>

                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                        {{ request()->routeIs('dashboard') ? 'bg-secondary-container text-on-secondary-container border-l-4 border-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span>Dashboard</span>
                </a>
            </nav>

            {{-- Profil & Logout --}}
            <div class="p-4 border-t border-outline-variant" x-data="{ open: false }">
                <div class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-surface-container-low transition-colors">
                        <div
                            class="w-9 h-9 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-semibold text-sm flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 text-left overflow-hidden">
                            <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <span class="material-symbols-outlined text-lg text-on-surface-variant">unfold_more</span>
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
                                class="w-full flex items-center gap-2 text-left px-4 py-2 text-error hover:bg-error-container/30">
                                <span class="material-symbols-outlined text-lg">logout</span>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main wrapper --}}
    <div class="md:ml-64 min-h-screen flex flex-col">

        {{-- Top bar --}}
        <header class="sticky top-0 z-30 bg-surface-container-lowest/80 backdrop-blur shadow-sm">
            <div class="h-16 px-4 md:px-8 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="md:hidden text-on-surface-variant">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <a href="{{ route('dashboard') }}" class="md:hidden text-lg font-bold text-primary">Kaleungitan</a>
                </div>

                <div class="flex items-center gap-3 ml-auto">
                    @livewire('user.notification-bell')
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 md:px-8 py-8">
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
    </div>

    @livewireScripts
</body>

</html>