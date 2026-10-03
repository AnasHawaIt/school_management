<?php

namespace Modules\Library\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Modules\Library\Entities\Fine;

interface FineRepositoryInterface
{
    public function paginate(Request $request): LengthAwarePaginator;

    public function findById(int $id): Fine;

    public function findForUpdate(int $id): Fine;

    public function update(Fine $fine, array $data): Fine;

    public function create(array $data): Fine;
}
