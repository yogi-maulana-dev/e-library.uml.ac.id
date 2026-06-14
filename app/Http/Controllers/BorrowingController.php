<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Fine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function approve(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->book->available_copies < 1) {
            return back()->with('status', 'Stok buku tidak tersedia.');
        }

        $borrowing->update([
            'status' => 'borrowed',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);
        $borrowing->book()->decrement('available_copies');

        return back()->with('status', 'Peminjaman disetujui.');
    }

    public function returnBook(Borrowing $borrowing): RedirectResponse
    {
        $daysOverdue = max(0, now()->startOfDay()->diffInDays($borrowing->due_date->startOfDay(), false) * -1);
        $borrowing->update([
            'status' => 'returned',
            'returned_date' => now(),
            'is_overdue' => $daysOverdue > 0,
        ]);
        $borrowing->book()->increment('available_copies');

        if ($daysOverdue > 0) {
            Fine::firstOrCreate(
                ['borrowing_id' => $borrowing->id],
                [
                    'user_id' => $borrowing->user_id,
                    'amount' => min($daysOverdue * 5000, 50000),
                    'days_overdue' => $daysOverdue,
                    'reason' => 'Denda keterlambatan pengembalian buku.',
                    'status' => 'pending',
                ]
            );
        }

        return back()->with('status', 'Buku berhasil dikembalikan.');
    }
}
