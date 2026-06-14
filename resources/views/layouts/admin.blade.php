<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin E-Library UML')</title>
    @include('layouts.partials.assets')
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="min-h-screen lg:flex">
        <div class="hidden lg:block">
            @include('layouts.partials.sidebar')
        </div>
        <main class="flex-1 p-4 lg:p-8">
            <div class="mb-5 flex items-center justify-between rounded-md border bg-white p-4 lg:hidden">
                <a href="{{ route('dashboard') }}" class="font-bold text-[#006633]">E-Library UML</a>
                <a href="{{ route('dashboard') }}" class="rounded-md bg-[#006633] px-3 py-2 text-sm font-semibold text-white">Dashboard</a>
            </div>
            @if(session('status'))
                <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
