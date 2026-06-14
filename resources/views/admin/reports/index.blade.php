@extends('layouts.admin')
@section('title', 'Laporan')
@section('content')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold">Laporan Perpustakaan</h1>
        <p class="text-sm text-slate-500">Export dan cetak laporan operasional.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.reports.export', 'excel') }}" class="rounded-md bg-[#006633] px-4 py-2 text-sm font-semibold text-white">Export Excel</a>
        <button onclick="window.print()" class="rounded-md border bg-white px-4 py-2 text-sm font-semibold">Print</button>
    </div>
</div>
<div class="mt-6 grid gap-4 md:grid-cols-3">
    <div class="rounded-md border bg-white p-5"><p class="text-sm text-slate-500">Buku</p><p class="text-3xl font-bold">{{ number_format($books) }}</p></div>
    <div class="rounded-md border bg-white p-5"><p class="text-sm text-slate-500">Anggota</p><p class="text-3xl font-bold">{{ number_format($members) }}</p></div>
    <div class="rounded-md border bg-white p-5"><p class="text-sm text-slate-500">Denda</p><p class="text-3xl font-bold">Rp {{ number_format($fines) }}</p></div>
</div>
<div class="mt-6 overflow-x-auto rounded-md border bg-white">
    <table class="w-full text-sm">
        <thead><tr class="border-b text-left"><th class="p-3">Anggota</th><th>Buku</th><th>Tanggal</th><th>Jatuh Tempo</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($borrowings as $borrowing)
                <tr class="border-b"><td class="p-3">{{ $borrowing->user?->name }}</td><td>{{ $borrowing->book?->title }}</td><td>{{ $borrowing->borrow_date?->format('d/m/Y') }}</td><td>{{ $borrowing->due_date?->format('d/m/Y') }}</td><td>{{ $borrowing->status }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
