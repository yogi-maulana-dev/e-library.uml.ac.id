<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Borrowing;
use App\Models\Ebook;
use App\Models\Fine;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'cards' => [
                'Buku' => Book::count(),
                'Anggota' => User::count(),
                'Peminjaman aktif' => Borrowing::whereIn('status', ['pending', 'approved', 'borrowed'])->count(),
                'Denda belum lunas' => Fine::where('status', 'pending')->sum('amount'),
                'Kategori' => BookCategory::count(),
                'Ebook' => Ebook::count(),
            ],
            'latestBorrowings' => Borrowing::with(['user', 'book'])->latest()->limit(8)->get(),
            'popularBooks' => Book::with('category')->orderByDesc('review_count')->limit(6)->get(),
        ]);
    }
}
