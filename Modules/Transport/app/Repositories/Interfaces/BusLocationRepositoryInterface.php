<?php


namespace Modules\Transport\app\Repositories\Interfaces;

use Illuminate\Http\Request;

interface BusLocationRepositoryInterface
{
    public function getAll(Request $request);

    public function create(array $data);

    public function find($id);

    public function getByBus($busId, Request $request);

    public function getLatestByBus($busId);

    public function update($id, array $data);

    public function delete($id);
}
