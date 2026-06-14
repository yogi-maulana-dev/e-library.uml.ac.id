@extends('layouts.public')
@section('title', 'Kontak')
@section('content')
<section class="mx-auto grid max-w-7xl gap-8 px-4 py-10 lg:grid-cols-2">
    <div>
        <h1 class="text-3xl font-bold">Kontak Perpustakaan</h1>
        <p class="mt-3 text-slate-600">Kirim kritik, saran, atau pertanyaan layanan perpustakaan.</p>
        <div class="mt-6 rounded-md border bg-white p-5 text-sm">
            <p><strong>Alamat:</strong> Universitas Muhammadiyah Lampung, Bandar Lampung</p>
            <p><strong>Email:</strong> perpustakaan@uml.ac.id</p>
            <p><strong>Jam:</strong> Senin - Jumat, 08.00 - 16.00 WIB</p>
        </div>
    </div>
    <form method="POST" action="{{ route('contact.store') }}" class="rounded-md border bg-white p-5">
        @csrf
        <input name="name" class="mb-3 w-full rounded-md border-slate-200" placeholder="Nama" required>
        <input name="email" type="email" class="mb-3 w-full rounded-md border-slate-200" placeholder="Email" required>
        <textarea name="message" rows="6" class="mb-3 w-full rounded-md border-slate-200" placeholder="Pesan" required></textarea>
        <button class="rounded-md bg-[#006633] px-5 py-3 font-semibold text-white">Kirim Pesan</button>
    </form>
</section>
@endsection
