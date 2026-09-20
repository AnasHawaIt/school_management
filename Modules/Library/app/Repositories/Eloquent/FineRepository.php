<?php

namespace Modules\Library\app\Repositories\Eloquent;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Modules\Library\app\Entities\Fine;
use Modules\Library\app\Repositories\Interfaces\FineRepositoryInterface;

class FineRepository implements FineRepositoryInterface
{
    public function paginate(Request $request): LengthAwarePaginator
    {
        return Fine::query()
            ->with([
                'transaction.member.user',
                'paidBy',
                'waivedBy',
            ])
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'status',
                    $request->string('status')->toString()
                )
            )
            ->latest()
            ->paginate(
                min(
                    (int) $request->get('per_page', 10),
                    100
                )
            );
    }

    public function findById(int $id): Fine
    {
        return Fine::query()
            ->with([
                'transaction.member.user',
                'paidBy',
                'waivedBy',
            ])
            ->findOrFail($id);
    }

    public function findForUpdate(int $id): Fine
    {
        return Fine::query()
            ->lockForUpdate()
            ->findOrFail($id);
    }

    public function update(Fine $fine, array $data): Fine
    {
        $fine->update($data);

        return $fine->fresh();
    }

    public function create(array $data): Fine
    {
        return Fine::create($data);
    }
}
