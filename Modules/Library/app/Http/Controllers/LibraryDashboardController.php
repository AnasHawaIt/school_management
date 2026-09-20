<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Library\app\Enums\BookCopiesStatus;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;
use Modules\Library\Entities\Borrowing;
use Modules\Library\Entities\Fine;
use Modules\Library\Entities\Member;
use Modules\Library\Entities\Reservation;

class LibraryDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('library.dashboard', [
            'stats' => [
                'books' => Book::count(),
                'available_copies' => BookCopy::where('status', 'available')->count(),
                'active_loans' => Borrowing::whereIn('status', ['borrowed', 'late'])->count(),
                'members' => Member::where('status', 'active')->count(),
                'overdue' => Borrowing::where('status', 'late')->count(),
                'unpaid_fines' => Fine::where('status', 'unpaid')->sum('amount'),
                'reservations' => Reservation::where('status', 'pending')->count(),
            ],
            'recentBorrowings' => Borrowing::with(['book', 'member.user'])
                ->latest()
                ->limit(8)
                ->get(),
            'lowStockBooks' => Book::withCount([
                'copies as available_copies_count' => fn ($query) => $query->where('status', BookCopiesStatus::AVAILABLE),
            ])

                ->orderByDesc('available_copies_count')
                ->limit(5)
                ->get(),
        ]);
    }
}
