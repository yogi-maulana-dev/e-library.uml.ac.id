@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('content')
<div class="flex flex-wrap items-center justify-between gap-3">
    <div>
        <p class="text-sm font-semibold text-[#006633]">Admin Panel</p>
        <h1 class="text-3xl font-bold">Overview Operasional</h1>
        <p class="text-sm text-slate-500">Alur kerja: transaksi, master data, konten portal, laporan, sistem.</p>
    </div>
    <a href="{{ route('admin.reports.index') }}" class="rounded-md bg-[#006633] px-4 py-2 text-sm font-semibold text-white">Laporan</a>
</div>

<div class="mt-5 grid gap-3 md:grid-cols-5">
    <a href="{{ route('admin.crud.index', 'borrowings') }}" class="rounded-md border border-emerald-100 bg-white p-4 text-sm font-semibold hover:bg-emerald-50">1. Transaksi</a>
    <a href="{{ route('admin.crud.index', 'books') }}" class="rounded-md border border-emerald-100 bg-white p-4 text-sm font-semibold hover:bg-emerald-50">2. Master Buku</a>
    <a href="{{ route('admin.crud.index', 'news') }}" class="rounded-md border border-emerald-100 bg-white p-4 text-sm font-semibold hover:bg-emerald-50">3. Konten Portal</a>
    <a href="{{ route('admin.reports.index') }}" class="rounded-md border border-emerald-100 bg-white p-4 text-sm font-semibold hover:bg-emerald-50">4. Laporan</a>
    <a href="{{ route('admin.crud.index', 'settings') }}" class="rounded-md border border-emerald-100 bg-white p-4 text-sm font-semibold hover:bg-emerald-50">5. Sistem</a>
</div>

<div class="mt-6 grid gap-4 md:grid-cols-3">
    @foreach($cards as $label => $value)
        <div class="rounded-md border bg-white p-5">
            <p class="text-sm text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-[#006633]">{{ is_numeric($value) ? number_format($value) : $value }}</p>
        </div>
    @endforeach
</div>
<div class="mt-6 grid gap-6 lg:grid-cols-2">
    <section class="rounded-md border bg-white p-5">
        <h2 class="font-bold">Peminjaman Terbaru</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b text-left"><th class="py-2">Anggota</th><th>Buku</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($latestBorrowings as $borrowing)
                        <tr class="border-b">
                            <td class="py-2">{{ $borrowing->user?->name }}</td>
                            <td>{{ $borrowing->book?->title }}</td>
                            <td>{{ $borrowing->status }}</td>
                            <td class="flex gap-2 py-2">
                                @if($borrowing->status === 'pending')
                                    <form method="POST" action="{{ route('admin.borrowings.approve', $borrowing) }}">@csrf<button class="rounded bg-emerald-600 px-2 py-1 text-xs text-white">Approve</button></form>
                                @endif
                                @if(in_array($borrowing->status, ['approved','borrowed'], true))
                                    <form method="POST" action="{{ route('admin.borrowings.return', $borrowing) }}">@csrf<button class="rounded bg-slate-700 px-2 py-1 text-xs text-white">Return</button></form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <section class="rounded-md border bg-white p-5">
        <h2 class="font-bold">Buku Populer</h2>
        <div class="mt-4 space-y-3">
            @foreach($popularBooks as $book)
                <div class="flex justify-between border-b pb-2 text-sm">
                    <span>{{ $book->title }}</span>
                    <span class="text-slate-500">{{ $book->category?->name }}</span>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
