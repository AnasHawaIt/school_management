<?php

namespace Modules\Library\Repositories\Interfaces;

interface BookRepositoryInterface
{
    public function getBookOnlyTrashed();
    public function restore($id);
    public function forceDelete($id);
    public function getAll($request);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
