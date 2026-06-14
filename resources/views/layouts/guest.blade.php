<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="E-Library Universitas Muhammadiyah Lampung - Portal Perpustakaan Digital">
        <meta name="keywords" content="perpustakaan, e-library, buku digital, UML">
        <meta name="robots" content="noindex, follow">

        <title>{{ config('app.name', 'E-Library UML') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @include('layouts.partials.assets')
        
        <style>
            * {
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }
            input, textarea { -webkit-user-select: text; -moz-user-select: text; -ms-user-select: text; user-select: text; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Security: Disable right-click, dev tools, etc -->
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50">
            <!-- Logo Section -->
            <div class="mb-8 text-center">
                <a href="/" class="inline-block">
                    <img src="{{ asset('images/logo_uml.png') }}" alt="UML Logo" class="h-20 w-20 object-contain mb-4 drop-shadow-lg" />
                    <div class="text-2xl font-bold text-emerald-900">E-Library</div>
                    <div class="text-sm text-emerald-600">Universitas Muhammadiyah Lampung</div>
                </a>
            </div>

            <!-- Login Card -->
            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-xl border border-emerald-100">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 text-center">Masuk ke Akun Anda</h1>
                    <p class="text-center text-gray-600 text-sm mt-2">Silakan masuk untuk mengakses perpustakaan digital</p>
                </div>
                
                {{ $slot }}
                
                <!-- Register Link -->
                <div class="mt-6 text-center border-t border-gray-200 pt-6">
                    <p class="text-sm text-gray-600">
                        Belum memiliki akun? 
                        <a href="{{ route('register') }}" class="font-semibold text-emerald-600 hover:text-emerald-700 transition">
                            Daftar di sini
                        </a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-gray-600">
                <p>&copy; {{ date('Y') }} Perpustakaan UML. Semua hak dilindungi.</p>
            </div>
        </div>
    </body>
</html>
