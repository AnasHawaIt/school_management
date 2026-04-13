<?php

namespace Modules\Library\Repositories\Interfaces;

interface CategoryRepositoryInterface
{
    public function getCategoryOnlyTrashed();
    public function restore($id);
    public function forceDelete($id);
    public function getAll($request);
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);

}
