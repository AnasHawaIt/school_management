<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-600">Library operations</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">Library dashboard</h2>
                <p class="mt-1 text-sm text-slate-500">A live view of circulation, inventory and member activity.</p>
            </div>
            <a href="{{ url('/api/library/books') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                Open catalog API
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['label' => 'Catalog books', 'value' => $stats['books'], 'hint' => 'active titles', 'dot' => 'bg-indigo-500'],
                    ['label' => 'Available copies', 'value' => $stats['available_copies'], 'hint' => 'ready to lend', 'dot' => 'bg-emerald-500'],
                    ['label' => 'Active loans', 'value' => $stats['active_loans'], 'hint' => $stats['overdue'].' overdue', 'dot' => 'bg-amber-500'],
                    ['label' => 'Active members', 'value' => $stats['members'], 'hint' => $stats['reservations'].' pending reservations', 'dot' => 'bg-violet-500'],
                ] as $card)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <p class="text-sm font-medium text-slate-500">{{ $card['label'] }}</p>
                            <span class="h-2.5 w-2.5 rounded-full {{ $card['dot'] }}"></span>
                        </div>
                        <p class="mt-3 text-3xl font-bold text-slate-900">{{ number_format($card['value']) }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $card['hint'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr]">
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                        <div>
                            <h3 class="font-semibold text-slate-900">Recent circulation</h3>
                            <p class="text-xs text-slate-500">The latest borrowing activity</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $recentBorrowings->count() }} records</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-5 py-3 font-semibold">Book</th>
                                <th class="px-5 py-3 font-semibold">Member</th>
                                <th class="px-5 py-3 font-semibold">Due</th>
                                <th class="px-5 py-3 font-semibold">Status</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                            @forelse ($recentBorrowings as $borrowing)
                                <tr class="hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-900">{{ $borrowing->book?->title ?? 'Deleted book' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $borrowing->member?->user?->full_name ?? $borrowing->member?->user?->name ?? 'Unknown member' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $borrowing->due_date?->format('M d, Y') ?? '—' }}</td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $borrowing->status === 'late' ? 'bg-red-100 text-red-700' : ($borrowing->status === 'returned' ? 'bg-slate-100 text-slate-600' : 'bg-emerald-100 text-emerald-700') }}">{{ ucfirst($borrowing->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-5 py-10 text-center text-slate-500">No circulation activity yet.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <div class="space-y-6">
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-slate-900">Attention required</h3>
                                <p class="text-xs text-slate-500">Items needing follow-up</p>
                            </div>
                            <span class="text-xl">!</span>
                        </div>
                        <div class="mt-5 space-y-3">
                            <div class="flex items-center justify-between rounded-xl bg-red-50 px-4 py-3">
                                <span class="text-sm text-red-800">Overdue loans</span>
                                <strong class="text-red-700">{{ $stats['overdue'] }}</strong>
                            </div>
                            <div class="flex items-center justify-between rounded-xl bg-amber-50 px-4 py-3">
                                <span class="text-sm text-amber-800">Unpaid fines</span>
                                <strong class="text-amber-700">{{ number_format($stats['unpaid_fines'], 2) }}</strong>
                            </div>
                            <div class="flex items-center justify-between rounded-xl bg-violet-50 px-4 py-3">
                                <span class="text-sm text-violet-800">Pending reservations</span>
                                <strong class="text-violet-700">{{ $stats['reservations'] }}</strong>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Lowest availability</h3>
                        <div class="mt-4 space-y-4">
                            @forelse ($lowStockBooks as $book)
                                <div>
                                    <div class="flex justify-between gap-4 text-sm">
                                        <span class="truncate font-medium text-slate-700">{{ $book->title }}</span>
                                        <span class="shrink-0 text-slate-500">{{ $book->available_copies_count }} available</span>
                                    </div>
                                    <div class="mt-2 h-1.5 rounded-full bg-slate-100">
                                        <div class="h-1.5 rounded-full bg-indigo-500" style="width: {{ $book->copies > 0 ? min(100, ($book->available_copies_count / $book->copies) * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">No books in the catalog.</p>
                            @endforelse
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
