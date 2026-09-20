<?php

namespace Modules\Library\app\Repositories\Interfaces;

use Illuminate\Http\Request;

interface BookRepositoryInterface
{
    public function getBookOnlyTrashed();
    public function restore($id);
    public function forceDelete($id);
    public function isAvailable(int $bookId): bool;
    public function availableCopiesCount(int $bookId): int;
    public function getAll(Request $request);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
