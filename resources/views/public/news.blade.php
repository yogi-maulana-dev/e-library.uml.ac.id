@extends('layouts.public')
@section('title', 'Berita')
@section('content')
<section class="mx-auto max-w-7xl px-4 py-10">
    <h1 class="text-3xl font-bold">Berita Perpustakaan</h1>
    <div class="mt-6 grid gap-4 md:grid-cols-3">
        @foreach($news as $item)
            <article class="rounded-md border bg-white p-5">
                <p class="text-xs text-[#D4AF37]">{{ $item->published_at?->format('d M Y') }}</p>
                <h2 class="mt-2 font-bold">{{ $item->title }}</h2>
                <p class="mt-2 text-sm text-slate-500">{{ $item->excerpt }}</p>
            </article>
        @endforeach
    </div>
    <div class="mt-6">{{ $news->links() }}</div>
</section>
@endsection
