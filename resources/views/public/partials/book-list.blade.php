<div class="grid gap-4 md:grid-cols-3">
    @forelse($books as $book)
        <a href="{{ route('books.show', $book) }}" class="rounded-md border border-emerald-100 bg-white p-4 hover:border-[#008F4C]">
            <div class="grid aspect-[3/4] place-items-center rounded-md bg-[#E8F5E9] p-4 text-center text-sm font-semibold text-[#006633]">{{ $book->title }}</div>
            <h3 class="mt-3 font-bold">{{ $book->title }}</h3>
            <p class="text-sm text-slate-500">{{ $book->authors->pluck('name')->join(', ') ?: 'Penulis belum diisi' }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ $book->category?->name }} - Stok {{ $book->available_copies }}</p>
        </a>
    @empty
        <p class="text-sm text-slate-500">Koleksi tidak ditemukan.</p>
    @endforelse
</div>
<div class="mt-6">{{ $books->links() }}</div>
