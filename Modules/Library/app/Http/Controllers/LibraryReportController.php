<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Library\Entities\Borrowing;
use Modules\Library\Entities\Fine;

class LibraryReportController extends Controller
{
    public function circulation(Request $request)
    {
        $from = $request->date('from')?->startOfDay() ?? now()->subDays(30)->startOfDay();
        $to = $request->date('to')?->endOfDay() ?? now()->endOfDay();

        return response()->json([
            'period' => ['from' => $from->toDateString(), 'to' => $to->toDateString()],
            'borrowings' => Borrowing::whereBetween('borrow_date', [$from->toDateString(), $to->toDateString()])->count(),
            'active' => Borrowing::whereIn('status', ['borrowed', 'late'])->count(),
            'overdue' => Borrowing::where('status', 'late')->count(),
            'fines' => [
                'unpaid' => Fine::where('status', 'unpaid')->sum('amount'),
                'paid' => Fine::where('status', 'paid')->sum('amount'),
                'waived' => Fine::where('status', 'waived')->sum('amount'),
            ],
            'most_borrowed' => Borrowing::select('book_id', DB::raw('count(*) as borrowings'))
                ->with('book:id,title')
                ->whereBetween('borrow_date', [$from->toDateString(), $to->toDateString()])
                ->groupBy('book_id')
                ->orderByDesc('borrowings')
                ->limit(10)
                ->get(),
        ]);
    }
}
