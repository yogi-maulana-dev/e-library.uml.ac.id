<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Bookmark;
use App\Models\Borrowing;
use App\Models\Ebook;
use App\Models\Event;
use App\Models\FAQ;
use App\Models\Favorite;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Repository;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        return view('public.home', [
            'sliders' => Slider::where('is_active', true)->orderBy('order')->get(),
            'featuredBooks' => Book::with(['category', 'authors'])->where('is_active', true)->latest()->limit(8)->get(),
            'news' => News::where('is_published', true)->latest('published_at')->limit(3)->get(),
            'events' => Event::where('is_published', true)->latest('start_date')->limit(4)->get(),
            'faqs' => FAQ::where('is_active', true)->orderBy('order')->limit(6)->get(),
            'testimonials' => Testimonial::where('is_approved', true)->orderBy('order')->limit(3)->get(),
            'galleries' => Gallery::where('is_active', true)->orderBy('order')->limit(4)->get(),
            'stats' => $this->statsPayload(),
        ]);
    }

    public function profile(): View
    {
        return view('public.profile');
    }

    public function collections(Request $request): View
    {
        $books = $this->bookQuery($request)->paginate(9)->withQueryString();

        if ($request->ajax()) {
            return view('public.partials.book-list', compact('books'));
        }

        return view('public.collections', [
            'books' => $books,
            'categories' => BookCategory::where('is_active', true)->orderBy('name')->get(),
            'years' => Book::query()->select('publication_year')->whereNotNull('publication_year')->distinct()->orderByDesc('publication_year')->pluck('publication_year'),
        ]);
    }

    public function book(Book $book): View
    {
        $book->load(['category', 'authors', 'publisher', 'shelf']);

        return view('public.book-detail', [
            'book' => $book,
            'related' => Book::with('authors')->where('category_id', $book->category_id)->whereKeyNot($book->id)->limit(4)->get(),
        ]);
    }

    public function ebooks(Request $request): View
    {
        return view('public.ebooks', [
            'ebooks' => Ebook::where('is_active', true)->latest()->paginate(10)->withQueryString(),
        ]);
    }

    public function repositories(Request $request): View
    {
        return view('public.repositories', [
            'repositories' => Repository::where('is_active', true)->latest()->paginate(10)->withQueryString(),
            'categories' => BookCategory::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function news(): View
    {
        return view('public.news', [
            'news' => News::where('is_published', true)->latest('published_at')->paginate(9),
        ]);
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function contactStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        DB::table('activity_logs')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => auth()->id(),
            'action' => 'contact.message',
            'subject_type' => 'contact',
            'description' => $data['name'].' mengirim pesan kontak.',
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('status', 'Pesan berhasil dikirim. Tim perpustakaan akan menghubungi Anda.');
    }

    public function borrow(Request $request, Book $book): RedirectResponse
    {
        abort_unless(auth()->check(), 403);

        if ($book->available_copies < 1) {
            return back()->with('status', 'Stok buku sedang tidak tersedia.');
        }

        Borrowing::create([
            'user_id' => $request->user()->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(14),
            'status' => 'pending',
            'notes' => 'Diajukan melalui portal anggota.',
        ]);

        return back()->with('status', 'Pengajuan peminjaman berhasil dibuat dan menunggu approval.');
    }

    public function toggleBookmark(Request $request, Book $book): RedirectResponse
    {
        abort_unless(auth()->check(), 403);
        $bookmark = Bookmark::where('user_id', $request->user()->id)->where('book_id', $book->id)->first();
        $bookmark ? $bookmark->delete() : Bookmark::create(['user_id' => $request->user()->id, 'book_id' => $book->id]);

        return back()->with('status', 'Bookmark diperbarui.');
    }

    public function toggleFavorite(Request $request, Book $book): RedirectResponse
    {
        abort_unless(auth()->check(), 403);
        $favorite = Favorite::where('user_id', $request->user()->id)->where('book_id', $book->id)->first();
        $favorite ? $favorite->delete() : Favorite::create(['user_id' => $request->user()->id, 'book_id' => $book->id]);

        return back()->with('status', 'Favorite diperbarui.');
    }

    public function apiSearch(Request $request): JsonResponse
    {
        return response()->json(
            $this->bookQuery($request)->limit(8)->get()->map(fn (Book $book) => [
                'title' => $book->title,
                'url' => route('books.show', $book),
                'meta' => $book->publication_year.' - '.$book->category?->name,
            ])
        );
    }

    public function apiStats(): JsonResponse
    {
        return response()->json($this->statsPayload());
    }

    private function bookQuery(Request $request)
    {
        return Book::with(['category', 'authors'])
            ->where('is_active', true)
            ->search($request->string('q')->toString())
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->category))
            ->when($request->filled('year'), fn ($query) => $query->where('publication_year', $request->year))
            ->latest();
    }

    private function statsPayload(): array
    {
        return [
            'books' => Book::count(),
            'members' => User::count(),
            'borrowings' => Borrowing::count(),
            'ebooks' => Ebook::count(),
        ];
    }
}
