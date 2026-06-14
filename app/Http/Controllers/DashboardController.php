<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Bookmark;
use App\Models\Borrowing;
use App\Models\Ebook;
use App\Models\Favorite;
use App\Models\Fine;
use App\Models\News;
use App\Models\Repository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $isLibraryTeam = $user->hasAnyRole(['Super Admin', 'Librarian', 'Staff']);

        return view('dashboard', [
            'user' => $user,
            'isLibraryTeam' => $isLibraryTeam,
            'roleName' => $user->getRoleNames()->first() ?? 'Anggota',
            'adminCards' => $this->adminCards(),
            'memberCards' => $this->memberCards($user->id),
            'latestBorrowings' => $isLibraryTeam
                ? Borrowing::with(['user', 'book'])->latest()->limit(8)->get()
                : Borrowing::with('book')->where('user_id', $user->id)->latest()->limit(8)->get(),
            'recommendedBooks' => Book::with(['category', 'authors'])->where('is_active', true)->latest()->limit(5)->get(),
            'latestNews' => News::where('is_published', true)->latest('published_at')->limit(4)->get(),
            'pendingApprovals' => Borrowing::with(['user', 'book'])->where('status', 'pending')->latest()->limit(5)->get(),
            'unpaidFines' => $isLibraryTeam
                ? Fine::with('user')->where('status', 'pending')->latest()->limit(5)->get()
                : Fine::where('user_id', $user->id)->where('status', 'pending')->latest()->limit(5)->get(),
        ]);
    }

    private function adminCards(): array
    {
        return [
            ['label' => 'Total Buku', 'value' => Book::count(), 'tone' => 'emerald'],
            ['label' => 'Anggota Aktif', 'value' => User::where('is_active', true)->count(), 'tone' => 'sky'],
            ['label' => 'Peminjaman Pending', 'value' => Borrowing::where('status', 'pending')->count(), 'tone' => 'amber'],
            ['label' => 'Sedang Dipinjam', 'value' => Borrowing::whereIn('status', ['approved', 'borrowed'])->count(), 'tone' => 'indigo'],
            ['label' => 'Denda Belum Lunas', 'value' => 'Rp '.number_format(Fine::where('status', 'pending')->sum('amount')), 'tone' => 'rose'],
            ['label' => 'Repository/Ebook', 'value' => Repository::count().' / '.Ebook::count(), 'tone' => 'slate'],
        ];
    }

    private function memberCards(string $userId): array
    {
        return [
            ['label' => 'Peminjaman Saya', 'value' => Borrowing::where('user_id', $userId)->count(), 'tone' => 'emerald'],
            ['label' => 'Menunggu Approval', 'value' => Borrowing::where('user_id', $userId)->where('status', 'pending')->count(), 'tone' => 'amber'],
            ['label' => 'Bookmark', 'value' => Bookmark::where('user_id', $userId)->count(), 'tone' => 'sky'],
            ['label' => 'Favorite', 'value' => Favorite::where('user_id', $userId)->count(), 'tone' => 'rose'],
        ];
    }
}
