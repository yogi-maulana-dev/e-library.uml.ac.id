@extends('layouts.public')

@section('title', 'E-Library Universitas Muhammadiyah Lampung')

@section('content')
<section class="bg-[#006633] text-white">
    <div class="mx-auto grid min-h-[520px] max-w-7xl items-center gap-8 px-4 py-12 lg:grid-cols-[1.1fr_.9fr]">
        <div>
            <p class="text-sm font-semibold uppercase text-[#D4AF37]">Modern Academic Digital Library</p>
            <h1 class="mt-4 text-4xl font-bold leading-tight md:text-6xl">E-Library Universitas Muhammadiyah Lampung</h1>
            <p class="mt-5 max-w-2xl text-lg text-emerald-50">Akses koleksi buku, ebook, repository ilmiah, peminjaman, dan informasi perpustakaan kampus dalam satu sistem terpadu.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('collections') }}" class="rounded-md bg-white px-5 py-3 font-semibold text-[#006633]">Jelajahi Koleksi</a>
                <a href="{{ route('repositories') }}" class="rounded-md border border-white/40 px-5 py-3 font-semibold">Repository</a>
            </div>
        </div>
        <div x-data="{ q:'', results:[] }" class="rounded-md bg-white p-5 text-slate-800 shadow-2xl">
            <label class="text-sm font-semibold">Cari buku realtime</label>
            <input x-model.debounce.300ms="q" @input="fetch('{{ route('api.books.search') }}?q='+encodeURIComponent(q)).then(r=>r.json()).then(d=>results=d)" class="mt-2 w-full rounded-md border-slate-200" placeholder="Judul, ISBN, atau penulis">
            <div class="mt-4 space-y-2">
                <template x-for="book in results" :key="book.url">
                    <a :href="book.url" class="block rounded-md border border-emerald-100 p-3 hover:bg-emerald-50">
                        <span class="block font-semibold" x-text="book.title"></span>
                        <span class="text-xs text-slate-500" x-text="book.meta"></span>
                    </a>
                </template>
                <p x-show="q && results.length === 0" class="text-sm text-slate-500">Tidak ada hasil.</p>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto grid max-w-7xl gap-4 px-4 py-8 md:grid-cols-4">
    @foreach($stats as $label => $value)
        <div class="rounded-md border border-emerald-100 bg-white p-5">
            <p class="text-sm capitalize text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-[#006633]">{{ number_format($value) }}</p>
        </div>
    @endforeach
</section>

<section class="mx-auto max-w-7xl px-4 py-8">
    <div class="flex items-end justify-between">
        <h2 class="text-2xl font-bold">Koleksi Pilihan</h2>
        <a href="{{ route('collections') }}" class="text-sm font-semibold text-[#006633]">Lihat semua</a>
    </div>
    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($featuredBooks as $book)
            <a href="{{ route('books.show', $book) }}" class="rounded-md border border-emerald-100 bg-white p-4 hover:border-[#008F4C]">
                <div class="grid aspect-[3/4] place-items-center rounded-md bg-[#E8F5E9] text-center text-sm font-semibold text-[#006633]">{{ $book->title }}</div>
                <h3 class="mt-3 font-bold">{{ $book->title }}</h3>
                <p class="text-sm text-slate-500">{{ $book->category?->name }} - {{ $book->publication_year }}</p>
            </a>
        @endforeach
    </div>
</section>

<section class="mx-auto grid max-w-7xl gap-6 px-4 py-8 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <h2 class="text-2xl font-bold">Berita Terbaru</h2>
        <div class="mt-5 grid gap-4 md:grid-cols-3">
            @forelse($news as $item)
                <article class="rounded-md border bg-white p-4">
                    <p class="text-xs text-[#D4AF37]">{{ $item->published_at?->format('d M Y') }}</p>
                    <h3 class="mt-2 font-bold">{{ $item->title }}</h3>
                    <p class="mt-2 text-sm text-slate-500">{{ $item->excerpt }}</p>
                </article>
            @empty
                <p class="text-sm text-slate-500">Belum ada berita.</p>
            @endforelse
        </div>
    </div>
    <div>
        <h2 class="text-2xl font-bold">FAQ</h2>
        <div class="mt-5 space-y-2">
            @foreach($faqs as $faq)
                <details class="rounded-md border bg-white p-4">
                    <summary class="cursor-pointer font-semibold">{{ $faq->question }}</summary>
                    <p class="mt-2 text-sm text-slate-600">{{ $faq->answer }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>
@endsection
