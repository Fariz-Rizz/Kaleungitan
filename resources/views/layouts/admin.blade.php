<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Kaleungitan Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-surface text-on-surface">

    <!-- Sidebar -->
    <aside class="fixed left-0 top-0 h-full w-[260px] z-40 bg-surface-container-lowest shadow-sm">
        <div class="flex flex-col h-full">
            <div class="p-6 flex flex-col gap-1">
                <h1 class="text-2xl font-bold text-primary">Kaleungitan</h1>
                <p class="text-sm text-on-surface-variant">Admin Console</p>
            </div>

            <nav class="flex-1 px-2 mt-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                          {{ request()->routeIs('admin.dashboard') ? 'bg-secondary-container text-on-secondary-container border-l-4 border-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.reports') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                         {{ request()->routeIs('admin.reports') ? 'bg-secondary-container text-on-secondary-container border-l-4 border-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined">report</span>
                    <span>Manage Reports</span>
                </a>

                <a href="{{ route('admin.claims') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                        {{ request()->routeIs('admin.claims') ? 'bg-secondary-container text-on-secondary-container border-l-4 border-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined">assignment_turned_in</span>
                    <span>Manage Claims</span>
                </a>

                <a href="{{ route('admin.categories') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                        {{ request()->routeIs('admin.categories') ? 'bg-secondary-container text-on-secondary-container border-l-4 border-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined">category</span>
                    <span>Manage Categories</span>
                </a>

                <a href="{{ route('admin.users') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                        {{ request()->routeIs('admin.users') ? 'bg-secondary-container text-on-secondary-container border-l-4 border-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined">group</span>
                    <span>Manage Users</span>
                </a>
            </nav>

            <div class="p-4">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-error hover:bg-error-container/30 transition-colors">
                        <span class="material-symbols-outlined">logout</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="ml-[260px] min-h-screen">

        <!-- Header -->
        <header
            class="sticky top-0 z-30 bg-surface/80 backdrop-blur border-b border-outline-variant px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2 w-96 bg-surface-container-low rounded-lg px-4 py-2">
                <span class="material-symbols-outlined text-on-surface-variant text-lg">search</span>
                <input type="text" placeholder="Cari laporan..." class="bg-transparent outline-none text-sm w-full">
            </div>

            <div class="flex items-center gap-4">
                <button class="relative p-2 rounded-lg hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <div class="flex items-center gap-2">
                    <div
                        class="w-9 h-9 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-semibold text-sm">
                        A
                    </div>
                    <span class="text-sm font-medium">Admin</span>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-8">
            @yield('content')
        </main>

    </div>
    @livewireScripts
</body>

</html>
