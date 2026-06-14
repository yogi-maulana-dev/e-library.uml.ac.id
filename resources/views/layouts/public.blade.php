<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'E-Library Universitas Muhammadiyah Lampung')">
    <title>@yield('title', 'E-Library UML')</title>
    @include('layouts.partials.assets')
</head>
<body class="bg-[#f7fbf8] text-slate-800 antialiased">
    <header class="sticky top-0 z-40 border-b border-emerald-100 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-md bg-[#006633] font-bold text-white">UML</span>
                <span>
                    <span class="block text-sm font-bold text-[#006633]">E-Library</span>
                    <span class="block text-xs text-slate-500">Universitas Muhammadiyah Lampung</span>
                </span>
            </a>
            <nav x-data="{ open:false }" class="relative">
                <button class="rounded-md border px-3 py-2 text-sm md:hidden" @click="open=!open">Menu</button>
                <div :class="open ? 'block' : 'hidden'" class="absolute right-0 mt-2 w-56 rounded-md border bg-white p-3 shadow-lg md:static md:block md:w-auto md:border-0 md:p-0 md:shadow-none">
                    <div class="flex flex-col gap-3 text-sm font-medium md:flex-row md:items-center">
                        <a href="{{ route('collections') }}">Koleksi</a>
                        <a href="{{ route('ebooks') }}">E-Library</a>
                        <a href="{{ route('repositories') }}">Repository</a>
                        <a href="{{ route('news') }}">Berita</a>
                        <a href="{{ route('contact') }}">Kontak</a>
                        @auth
                            <a href="{{ route('admin.dashboard') }}" class="rounded-md bg-[#006633] px-4 py-2 text-white">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-md bg-[#006633] px-4 py-2 text-white">Login</a>
                        @endauth
                    </div>
                </div>
            </nav>
        </div>
    </header>

    @if(session('status'))
        <div class="mx-auto mt-4 max-w-7xl px-4">
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="mt-16 bg-[#003d24] text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 md:grid-cols-3">
            <div>
                <h2 class="font-bold">E-Library UML</h2>
                <p class="mt-2 text-sm text-emerald-100">Portal perpustakaan digital, repository akademik, dan layanan peminjaman buku Universitas Muhammadiyah Lampung.</p>
            </div>
            <div class="text-sm text-emerald-100">
                <p class="font-semibold text-white">Jam Operasional</p>
                <p>Senin - Jumat, 08.00 - 16.00 WIB</p>
                <p>Sabtu, 08.00 - 12.00 WIB</p>
            </div>
            <div class="text-sm text-emerald-100">
                <p class="font-semibold text-white">Kontak</p>
                <p>Bandar Lampung, Lampung</p>
                <p>perpustakaan@uml.ac.id</p>
            </div>
        </div>
    </footer>
</body>
</html>
