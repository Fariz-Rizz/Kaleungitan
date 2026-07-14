@extends('layouts.user')

@section('title', 'My Profile')

@section('content')

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-on-surface">My Profile</h1>
        <p class="text-sm text-on-surface-variant mt-1">Informasi akun kamu, diambil dari data saat registrasi.</p>
    </div>

    {{-- Profile Summary Card --}}
    <div class="bg-surface-container-lowest p-5 md:p-6 rounded-xl shadow-sm border border-outline-variant mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div
                class="w-16 h-16 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold text-2xl shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div class="flex-1">
                <p class="text-lg font-semibold text-on-surface">{{ $user->name }}</p>
                <p class="text-sm text-on-surface-variant">{{ $user->email }}</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary capitalize">
                    {{ $user->role ?? 'user' }}
                </span>

                @if ($user->is_active ?? true)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                        Aktif
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-error-container text-error">
                        Nonaktif
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6 pt-5 border-t border-outline-variant">
            <div>
                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Nama Lengkap</p>
                <p class="text-sm text-on-surface mt-1">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Email</p>
                <p class="text-sm text-on-surface mt-1">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Bergabung Sejak</p>
                <p class="text-sm text-on-surface mt-1">{{ $user->created_at->translatedFormat('d F Y') }}</p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        {{-- Update Profile Information --}}
        <div class="bg-surface-container-lowest p-5 md:p-6 rounded-xl shadow-sm border border-outline-variant">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Update Password --}}
        <div class="bg-surface-container-lowest p-5 md:p-6 rounded-xl shadow-sm border border-outline-variant">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Delete Account --}}
        <div class="bg-surface-container-lowest p-5 md:p-6 rounded-xl shadow-sm border border-outline-variant">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>

@endsection
