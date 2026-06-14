@extends('layouts.public')

@section('title', $book->title)

@section('content')
<section class="mx-auto grid max-w-7xl gap-8 px-4 py-10 lg:grid-cols-[360px_1fr]">
    <div class="grid aspect-[3/4] place-items-center rounded-md bg-[#E8F5E9] p-8 text-center text-2xl font-bold text-[#006633]">{{ $book->title }}</div>
    <div>
        <p class="text-sm font-semibold text-[#D4AF37]">{{ $book->category?->name }}</p>
        <h1 class="mt-2 text-4xl font-bold">{{ $book->title }}</h1>
        <p class="mt-3 text-slate-600">{{ $book->description ?: 'Deskripsi buku belum tersedia.' }}</p>
        <dl class="mt-6 grid gap-3 text-sm md:grid-cols-2">
            <div><dt class="font-semibold">Penulis</dt><dd>{{ $book->authors->pluck('name')->join(', ') ?: '-' }}</dd></div>
            <div><dt class="font-semibold">Penerbit</dt><dd>{{ $book->publisher?->name ?: '-' }}</dd></div>
            <div><dt class="font-semibold">Tahun</dt><dd>{{ $book->publication_year ?: '-' }}</dd></div>
            <div><dt class="font-semibold">Rak</dt><dd>{{ $book->shelf?->code ?: '-' }}</dd></div>
            <div><dt class="font-semibold">Status</dt><dd>{{ $book->status }} - stok {{ $book->available_copies }}</dd></div>
            <div><dt class="font-semibold">Rating</dt><dd>{{ $book->rating }}/5</dd></div>
        </dl>
        <div class="mt-8 flex flex-wrap gap-3">
            @auth
                <form method="POST" action="{{ route('books.borrow', $book) }}">@csrf<button class="rounded-md bg-[#006633] px-5 py-3 font-semibold text-white">Ajukan Pinjam</button></form>
                <form method="POST" action="{{ route('books.bookmark', $book) }}">@csrf<button class="rounded-md border px-5 py-3 font-semibold">Bookmark</button></form>
                <form method="POST" action="{{ route('books.favorite', $book) }}">@csrf<button class="rounded-md border px-5 py-3 font-semibold">Favorite</button></form>
            @else
                <a href="{{ route('login') }}" class="rounded-md bg-[#006633] px-5 py-3 font-semibold text-white">Login untuk Meminjam</a>
            @endauth
        </div>
    </div>
</section>
@endsection
