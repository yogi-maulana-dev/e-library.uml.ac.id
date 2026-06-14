<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCrudController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/profil', [PublicController::class, 'profile'])->name('profile.public');
Route::get('/koleksi', [PublicController::class, 'collections'])->name('collections');
Route::get('/koleksi/{book}', [PublicController::class, 'book'])->name('books.show');
Route::get('/e-library', [PublicController::class, 'ebooks'])->name('ebooks');
Route::get('/repository', [PublicController::class, 'repositories'])->name('repositories');
Route::get('/berita', [PublicController::class, 'news'])->name('news');
Route::get('/kontak', [PublicController::class, 'contact'])->name('contact');
Route::post('/kontak', [PublicController::class, 'contactStore'])->name('contact.store')->middleware('throttle:5,1');

Route::get('/api/books/search', [PublicController::class, 'apiSearch'])->name('api.books.search')->middleware('throttle:60,1');
Route::get('/api/stats', [PublicController::class, 'apiStats'])->name('api.stats')->middleware('throttle:60,1');

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/koleksi/{book}/pinjam', [PublicController::class, 'borrow'])->name('books.borrow');
    Route::post('/koleksi/{book}/bookmark', [PublicController::class, 'toggleBookmark'])->name('books.bookmark');
    Route::post('/koleksi/{book}/favorite', [PublicController::class, 'toggleFavorite'])->name('books.favorite');
});

Route::middleware(['auth', 'verified', 'role:Super Admin|Librarian|Staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('role:Super Admin|Librarian');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export')->middleware('role:Super Admin|Librarian');
    Route::post('/borrowings/{borrowing}/approve', [BorrowingController::class, 'approve'])->name('borrowings.approve')->middleware('role:Super Admin|Librarian');
    Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowings.return')->middleware('role:Super Admin|Librarian');
    Route::get('/{module}', [AdminCrudController::class, 'index'])->name('crud.index');
    Route::get('/{module}/create', [AdminCrudController::class, 'create'])->name('crud.create');
    Route::post('/{module}', [AdminCrudController::class, 'store'])->name('crud.store');
    Route::get('/{module}/{id}/edit', [AdminCrudController::class, 'edit'])->name('crud.edit');
    Route::put('/{module}/{id}', [AdminCrudController::class, 'update'])->name('crud.update');
    Route::delete('/{module}/{id}', [AdminCrudController::class, 'destroy'])->name('crud.destroy');
});

require __DIR__.'/auth.php';
