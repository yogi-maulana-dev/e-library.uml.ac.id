@extends('layouts.public')

@section('title', 'Koleksi Buku')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10" x-data="{ loading:false }">
    <h1 class="text-3xl font-bold">Koleksi Buku</h1>
    <form class="mt-6 grid gap-3 rounded-md border bg-white p-4 md:grid-cols-4" method="GET">
        <input name="q" value="{{ request('q') }}" class="rounded-md border-slate-200" placeholder="Cari buku">
        <select name="category" class="rounded-md border-slate-200">
            <option value="">Semua kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="year" class="rounded-md border-slate-200">
            <option value="">Semua tahun</option>
            @foreach($years as $year)
                <option value="{{ $year }}" @selected((string) request('year') === (string) $year)>{{ $year }}</option>
            @endforeach
        </select>
        <button class="rounded-md bg-[#006633] px-4 py-2 font-semibold text-white">Filter</button>
    </form>
    <div class="mt-6">
        @include('public.partials.book-list', ['books' => $books])
    </div>
</section>
@endsection
