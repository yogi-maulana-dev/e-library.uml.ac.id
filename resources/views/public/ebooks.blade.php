@extends('layouts.public')
@section('title', 'E-Library')
@section('content')
<section class="mx-auto max-w-7xl px-4 py-10">
    <h1 class="text-3xl font-bold">E-Library</h1>
    <div class="mt-6 space-y-3">
        @foreach($ebooks as $ebook)
            <div class="flex items-center justify-between rounded-md border bg-white p-4">
                <div><h2 class="font-bold">{{ $ebook->title }}</h2><p class="text-sm text-slate-500">{{ $ebook->file_type }} - {{ number_format($ebook->download_count) }} download</p></div>
                <a href="{{ asset('storage/'.$ebook->file_path) }}" class="rounded-md bg-[#006633] px-4 py-2 text-sm font-semibold text-white">Preview/Download</a>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $ebooks->links() }}</div>
</section>
@endsection
