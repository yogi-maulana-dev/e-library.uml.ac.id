@extends('layouts.public')
@section('title', 'Repository')
@section('content')
<section class="mx-auto max-w-7xl px-4 py-10">
    <h1 class="text-3xl font-bold">Repository Digital</h1>
    <div class="mt-6 space-y-3">
        @foreach($repositories as $repo)
            <div class="flex items-center justify-between rounded-md border bg-white p-4">
                <div><h2 class="font-bold">{{ $repo->title }}</h2><p class="text-sm text-slate-500">{{ $repo->file_type }} - {{ number_format($repo->download_count) }} download</p></div>
                <a href="{{ asset('storage/'.$repo->file_path) }}" class="rounded-md bg-[#006633] px-4 py-2 text-sm font-semibold text-white">Preview/Download</a>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $repositories->links() }}</div>
</section>
@endsection
