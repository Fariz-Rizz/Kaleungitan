<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Kaleungitan') }} — Sistem Barang Hilang &amp; Temuan Kampus</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-surface text-on-surface">

    {{-- HERO --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('{{ asset('images/kampus-umb.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/55 to-surface"></div>

        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 pt-24 pb-28 text-center">
            <span
                class="inline-block text-xs font-medium tracking-wide uppercase text-white/85 bg-white/10 border border-white/25 rounded-full px-3 py-1 mb-5">
                Sistem barang hilang &amp; temuan kampus
            </span>

            <h1 class="text-3xl sm:text-4xl font-bold text-white drop-shadow leading-tight">
                Kehilangan barang di kampus?<br class="hidden sm:block">
                Kaleungitan bantu kamu menemukannya.
            </h1>

            <p class="mt-4 text-white/85 text-sm sm:text-base max-w-xl mx-auto">
                Laporkan barang hilang atau temuan, telusuri daftar barang yang sudah diverifikasi admin,
                lalu ajukan klaim langsung lewat sistem.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                @guest
                    <a href="{{ route('register') }}"
                        class="w-full sm:w-auto px-6 py-3 rounded-lg bg-primary text-on-primary text-sm font-semibold hover:opacity-90 transition-opacity">
                        Daftar sekarang
                    </a>
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto px-6 py-3 rounded-lg bg-white/10 border border-white/30 text-white text-sm font-semibold hover:bg-white/20 transition-colors">
                        Masuk
                    </a>
                @else
                    <a href="{{ route('report-item') }}"
                        class="w-full sm:w-auto px-6 py-3 rounded-lg bg-primary text-on-primary text-sm font-semibold hover:opacity-90 transition-opacity">
                        Lapor barang
                    </a>
                    <a href="{{ route('browse-items') }}"
                        class="w-full sm:w-auto px-6 py-3 rounded-lg bg-white/10 border border-white/30 text-white text-sm font-semibold hover:bg-white/20 transition-colors">
                        Jelajahi barang
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- CARA KERJA --}}
    <section class="max-w-5xl mx-auto px-4 sm:px-6 py-16">
        <h2 class="text-xl sm:text-2xl font-semibold text-on-surface text-center mb-2">Cara kerja Kaleungitan</h2>
        <p class="text-sm text-on-surface-variant text-center mb-10 max-w-md mx-auto">
            Tiga langkah sederhana dari laporan masuk sampai barang kembali ke pemiliknya.
        </p>

        <div class="grid sm:grid-cols-3 gap-6">
            <div class="text-center px-2">
                <div
                    class="mx-auto mb-4 w-12 h-12 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-semibold">
                    1</div>
                <h3 class="font-medium text-on-surface mb-1">Laporkan</h3>
                <p class="text-sm text-on-surface-variant">Isi detail barang hilang atau temuan lengkap dengan foto,
                    lokasi, dan tanggal kejadian.</p>
            </div>
            <div class="text-center px-2">
                <div
                    class="mx-auto mb-4 w-12 h-12 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-semibold">
                    2</div>
                <h3 class="font-medium text-on-surface mb-1">Diverifikasi admin</h3>
                <p class="text-sm text-on-surface-variant">Tim admin kampus memeriksa setiap laporan sebelum tampil
                    ke publik.</p>
            </div>
            <div class="text-center px-2">
                <div
                    class="mx-auto mb-4 w-12 h-12 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-semibold">
                    3</div>
                <h3 class="font-medium text-on-surface mb-1">Klaim &amp; serah terima</h3>
                <p class="text-sm text-on-surface-variant">Cocokkan laporan, ajukan klaim, lalu ambil barang sesuai
                    prosedur yang berlaku.</p>
            </div>
        </div>
    </section>

    {{-- FITUR --}}
    <section class="bg-surface-container-low py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <h2 class="text-xl sm:text-2xl font-semibold text-on-surface text-center mb-10">Semua yang kamu butuhkan
                dalam satu sistem</h2>

            <div class="grid sm:grid-cols-2 gap-4">
                <div
                    class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 flex gap-4 items-start">
                    <span class="material-symbols-outlined text-primary text-3xl">report</span>
                    <div>
                        <h3 class="font-medium text-on-surface mb-1">Lapor kehilangan</h3>
                        <p class="text-sm text-on-surface-variant">Catat barang yang hilang agar bisa dicocokkan
                            dengan laporan temuan yang masuk.</p>
                    </div>
                </div>
                <div
                    class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 flex gap-4 items-start">
                    <span class="material-symbols-outlined text-primary text-3xl">add_a_photo</span>
                    <div>
                        <h3 class="font-medium text-on-surface mb-1">Lapor temuan</h3>
                        <p class="text-sm text-on-surface-variant">Serahkan barang yang kamu temukan agar cepat
                            kembali ke pemiliknya.</p>
                    </div>
                </div>
                <div
                    class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 flex gap-4 items-start">
                    <span class="material-symbols-outlined text-primary text-3xl">search</span>
                    <div>
                        <h3 class="font-medium text-on-surface mb-1">Jelajahi barang</h3>
                        <p class="text-sm text-on-surface-variant">Telusuri daftar barang temuan yang sudah
                            terverifikasi berdasarkan kategori dan lokasi.</p>
                    </div>
                </div>
                <div
                    class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 flex gap-4 items-start">
                    <span class="material-symbols-outlined text-primary text-3xl">verified_user</span>
                    <div>
                        <h3 class="font-medium text-on-surface mb-1">Klaim aman &amp; terverifikasi</h3>
                        <p class="text-sm text-on-surface-variant">Setiap klaim diawasi admin supaya barang hanya
                            diserahkan ke pemilik yang sah.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
   <footer class="border-t border-outline-variant py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 text-center text-sm text-on-surface-variant">
        <p>&copy; {{ date('Y') }} Kaleungitan — Universitas Muhammadiyah Bandung</p>
    </div>
</footer>
</body>

</html>
