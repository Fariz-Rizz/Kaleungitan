<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Kaleungitan') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    <div class="relative min-h-screen flex flex-col items-center justify-center px-4">

        {{-- Background foto kampus (khusus login/register user) --}}
        <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('{{ asset('images/kampus-umb.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/50 to-black/70"></div>

        {{-- Konten --}}
        <div class="relative z-10 w-full flex flex-col items-center">

            <div class="mb-6 text-center">
                <h1 class="text-3xl font-bold text-white drop-shadow">Kaleungitan</h1>
                <p class="text-sm text-white/85">Sistem Barang Hilang &amp; Temuan Kampus</p>
            </div>

            <div
                class="w-full sm:max-w-md bg-surface-container-lowest shadow-xl border border-outline-variant rounded-xl p-8">
                {{ $slot }}
            </div>

        </div>

    </div>

</body>

</html>
