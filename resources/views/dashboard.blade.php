<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-semibold text-[#006633]">E-Library Universitas Muhammadiyah Lampung</p>
                <h2 class="text-2xl font-bold text-slate-900">
                    Dashboard {{ $roleName }}
                </h2>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('home') }}" class="rounded-md border border-emerald-200 bg-white px-4 py-2 text-sm font-semibold text-[#006633]">Portal</a>
                @if($isLibraryTeam)
                    <a href="{{ route('admin.dashboard') }}" class="rounded-md bg-[#006633] px-4 py-2 text-sm font-semibold text-white">Admin Panel</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="bg-slate-100 py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-md bg-[#006633] text-white shadow-sm">
                <div class="grid gap-6 p-6 lg:grid-cols-[1fr_340px] lg:p-8">
                    <div>
                        <p class="text-sm font-semibold uppercase text-[#D4AF37]">Selamat datang, {{ $user->name }}</p>
                        <h1 class="mt-3 text-3xl font-bold md:text-4xl">
                            @if($isLibraryTeam)
                                Pusat kendali operasional perpustakaan UML.
                            @else
                                Ruang anggota untuk akses koleksi, pinjaman, dan aktivitas literasi.
                            @endif
                        </h1>
                        <p class="mt-4 max-w-3xl text-sm leading-6 text-emerald-50">
                            @if($isLibraryTeam)
                                Urutan kerja: cek status layanan, proses peminjaman, kelola koleksi/konten, lalu tutup dengan laporan.
                            @else
                                Urutan layanan: cari koleksi, ajukan peminjaman, pantau status, lalu simpan buku penting ke bookmark/favorite.
                            @endif
                        </p>
                    </div>
                    <div class="rounded-md border border-white/20 bg-white/10 p-5">
                        <p class="text-sm text-emerald-100">Role aktif</p>
                        <p class="mt-1 text-2xl font-bold">{{ $roleName }}</p>
                        <p class="mt-4 text-sm text-emerald-100">{{ $user->email }}</p>
                        <a href="{{ route('profile.edit') }}" class="mt-5 inline-block rounded-md bg-white px-4 py-2 text-sm font-semibold text-[#006633]">Edit Profil</a>
                    </div>
                </div>
            </section>

            <section class="mt-6">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900">1. Ringkasan Status</h3>
                    <span class="text-sm text-slate-500">Realtime dari database</span>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 {{ $isLibraryTeam ? 'xl:grid-cols-6' : 'xl:grid-cols-4' }}">
                @foreach($isLibraryTeam ? $adminCards : $memberCards as $card)
                    <div class="rounded-md border border-emerald-100 bg-white p-5 shadow-sm">
                        <p class="text-sm text-slate-500">{{ $card['label'] }}</p>
                        <p class="mt-2 text-2xl font-bold text-[#006633]">{{ $card['value'] }}</p>
                    </div>
                @endforeach
                </div>
            </section>

            <section class="mt-6 grid gap-6 lg:grid-cols-[1.2fr_.8fr]">
                <div class="rounded-md border bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-900">{{ $isLibraryTeam ? 'Peminjaman Terbaru' : 'Histori Peminjaman Saya' }}</h3>
                        @if($isLibraryTeam)
                            <a href="{{ route('admin.crud.index', 'borrowings') }}" class="text-sm font-semibold text-[#006633]">Kelola</a>
                        @endif
                    </div>
                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b bg-slate-50 text-left text-slate-500">
                                    @if($isLibraryTeam)<th class="px-3 py-2">Anggota</th>@endif
                                    <th class="px-3 py-2">Buku</th>
                                    <th class="px-3 py-2">Tanggal</th>
                                    <th class="px-3 py-2">Jatuh Tempo</th>
                                    <th class="px-3 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestBorrowings as $borrowing)
                                    <tr class="border-b">
                                        @if($isLibraryTeam)<td class="px-3 py-3">{{ $borrowing->user?->name }}</td>@endif
                                        <td class="px-3 py-3 font-medium">{{ $borrowing->book?->title }}</td>
                                        <td class="px-3 py-3">{{ $borrowing->borrow_date?->format('d/m/Y') }}</td>
                                        <td class="px-3 py-3">{{ $borrowing->due_date?->format('d/m/Y') }}</td>
                                        <td class="px-3 py-3">
                                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $borrowing->status }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-3 py-6 text-center text-slate-500">Belum ada data peminjaman.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-md border bg-white p-5 shadow-sm">
                        <h3 class="font-bold text-slate-900">2. Aksi Utama</h3>
                        <p class="mt-1 text-sm text-slate-500">Menu yang paling sering dipakai sesuai role.</p>
                        <div class="mt-4 grid gap-2">
                            <a href="{{ route('collections') }}" class="rounded-md border border-emerald-100 px-4 py-3 text-sm font-semibold hover:bg-emerald-50">Cari dan Pinjam Buku</a>
                            <a href="{{ route('ebooks') }}" class="rounded-md border border-emerald-100 px-4 py-3 text-sm font-semibold hover:bg-emerald-50">Buka E-Library</a>
                            <a href="{{ route('repositories') }}" class="rounded-md border border-emerald-100 px-4 py-3 text-sm font-semibold hover:bg-emerald-50">Repository Digital</a>
                            @if($isLibraryTeam)
                                <a href="{{ route('admin.crud.create', 'books') }}" class="rounded-md bg-[#006633] px-4 py-3 text-sm font-semibold text-white">Tambah Buku</a>
                                @if($user->hasAnyRole(['Super Admin', 'Librarian']))
                                    <a href="{{ route('admin.reports.index') }}" class="rounded-md bg-slate-800 px-4 py-3 text-sm font-semibold text-white">Laporan</a>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="rounded-md border bg-white p-5 shadow-sm">
                        <h3 class="font-bold text-slate-900">{{ $isLibraryTeam ? 'Denda Belum Lunas' : 'Tagihan Denda Saya' }}</h3>
                        <div class="mt-4 space-y-3">
                            @forelse($unpaidFines as $fine)
                                <div class="rounded-md bg-rose-50 p-3 text-sm">
                                    <p class="font-semibold text-rose-800">Rp {{ number_format($fine->amount) }}</p>
                                    <p class="text-rose-700">{{ $fine->reason }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">Tidak ada denda pending.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>

            @if($isLibraryTeam)
                <section class="mt-6 rounded-md border bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900">3. Pekerjaan Hari Ini</h3>
                            <p class="text-sm text-slate-500">Prioritas transaksi yang perlu ditindaklanjuti.</p>
                        </div>
                        <a href="{{ route('admin.crud.index', 'borrowings') }}" class="text-sm font-semibold text-[#006633]">Lihat semua</a>
                    </div>
                    <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-5">
                        @forelse($pendingApprovals as $borrowing)
                            <div class="rounded-md border border-amber-100 bg-amber-50 p-4">
                                <p class="text-sm font-bold">{{ $borrowing->book?->title }}</p>
                                <p class="mt-1 text-xs text-slate-600">{{ $borrowing->user?->name }}</p>
                                @if($user->hasAnyRole(['Super Admin', 'Librarian']))
                                    <form class="mt-3" method="POST" action="{{ route('admin.borrowings.approve', $borrowing) }}">
                                        @csrf
                                        <button class="rounded-md bg-[#006633] px-3 py-2 text-xs font-semibold text-white">Approve</button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Tidak ada approval pending.</p>
                        @endforelse
                    </div>
                </section>
            @endif

            <section class="mt-6 grid gap-6 lg:grid-cols-2">
                <div class="rounded-md border bg-white p-5 shadow-sm">
                    <h3 class="font-bold text-slate-900">{{ $isLibraryTeam ? '4. Koleksi Terbaru' : '3. Rekomendasi Koleksi' }}</h3>
                    <div class="mt-4 space-y-3">
                        @foreach($recommendedBooks as $book)
                            <a href="{{ route('books.show', $book) }}" class="flex items-center justify-between rounded-md border border-slate-100 p-3 hover:bg-emerald-50">
                                <span>
                                    <span class="block text-sm font-semibold">{{ $book->title }}</span>
                                    <span class="text-xs text-slate-500">{{ $book->category?->name }} - {{ $book->authors->pluck('name')->join(', ') }}</span>
                                </span>
                                <span class="text-xs font-semibold text-[#006633]">Detail</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-md border bg-white p-5 shadow-sm">
                    <h3 class="font-bold text-slate-900">{{ $isLibraryTeam ? '5. Berita Portal' : '4. Berita Perpustakaan' }}</h3>
                    <div class="mt-4 space-y-3">
                        @forelse($latestNews as $item)
                            <article class="rounded-md border border-slate-100 p-3">
                                <p class="text-xs text-[#D4AF37]">{{ $item->published_at?->format('d M Y') }}</p>
                                <h4 class="mt-1 text-sm font-semibold">{{ $item->title }}</h4>
                                <p class="mt-1 text-xs text-slate-500">{{ $item->excerpt }}</p>
                            </article>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada berita.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
