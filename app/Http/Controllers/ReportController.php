<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.reports.index', [
            'books' => Book::count(),
            'members' => User::count(),
            'borrowings' => Borrowing::with(['user', 'book'])->latest()->limit(50)->get(),
            'fines' => Fine::sum('amount'),
        ]);
    }

    public function export(string $type)
    {
        $rows = Borrowing::with(['user', 'book'])->latest()->get()->map(fn ($borrowing) => [
            $borrowing->id,
            $borrowing->user?->name,
            $borrowing->book?->title,
            $borrowing->borrow_date?->format('Y-m-d'),
            $borrowing->due_date?->format('Y-m-d'),
            $borrowing->status,
        ]);

        $csv = "ID,Anggota,Buku,Tanggal Pinjam,Jatuh Tempo,Status\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map(fn ($value) => '"'.str_replace('"', '""', (string) $value).'"', $row))."\n";
        }

        $extension = $type === 'excel' ? 'csv' : 'txt';

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=laporan-peminjaman.{$extension}",
        ]);
    }
}
