@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-on-surface">Profil Saya</h1>
        <p class="text-sm text-on-surface-variant mt-1">Kelola informasi akun dan keamanan kamu di sini.</p>
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
                <p class="text-xs text-on-surface-variant mt-1">
                    Bergabung sejak {{ $user->created_at->translatedFormat('d F Y') }}
                </p>
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
    </div>

    <div class="space-y-6">
        {{-- Update Profile Information --}}
        <div class="bg-surface-container-lowest p-5 md:p-6 rounded-xl shadow-sm border border-outline-variant">
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- Update Password --}}
        <div class="bg-surface-container-lowest p-5 md:p-6 rounded-xl shadow-sm border border-outline-variant">
            @include('profile.partials.update-password-form')
        </div>

        {{-- Delete Account (Danger Zone) --}}
        <div class="bg-error-container/10 p-5 md:p-6 rounded-xl shadow-sm border border-error/20">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

@endsection
