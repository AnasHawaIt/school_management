<?php

namespace Modules\Library\app\Repositories\Interfaces;

use Illuminate\Http\Request;

interface AuthorRepositoryInterface

{
    public function getAuthorOnlyTrashed();
    public function restore($id);
    public function forceDelete($id);
    public function getAll(Request $request);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
