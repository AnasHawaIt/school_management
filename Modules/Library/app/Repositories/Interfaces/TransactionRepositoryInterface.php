<?php


namespace Modules\Library\app\Repositories\Interfaces;

use Illuminate\Http\Request;

interface TransactionRepositoryInterface
{
    public function getTransactionOnlyTrashed();
    public function restore($id);
    public function forceDelete($id);
    public function getAll(Request $request);
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
