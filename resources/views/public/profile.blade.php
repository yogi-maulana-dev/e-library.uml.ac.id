@extends('layouts.public')
@section('title', 'Profil Perpustakaan')
@section('content')
<section class="mx-auto max-w-7xl px-4 py-10">
    <h1 class="text-3xl font-bold">Profil Perpustakaan UML</h1>
    <div class="mt-6 grid gap-6 md:grid-cols-2">
        @foreach(['Sejarah Perpustakaan', 'Visi Misi', 'Struktur Organisasi', 'SOP Layanan', 'Sambutan Kepala Perpustakaan', 'Staff Perpustakaan'] as $title)
            <article class="rounded-md border bg-white p-6">
                <h2 class="font-bold text-[#006633]">{{ $title }}</h2>
                <p class="mt-2 text-sm text-slate-600">Konten {{ strtolower($title) }} dapat dikelola melalui modul Halaman di admin panel.</p>
            </article>
        @endforeach
    </div>
</section>
@endsection
