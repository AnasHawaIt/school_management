<?php

namespace Modules\Library\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Library\app\Http\Resources\FineResource;
use Modules\Library\app\Entities\Fine;
use Modules\Library\app\Services\FineService;
use Modules\Library\app\Repositories\Interfaces\FineRepositoryInterface;

class FineController extends Controller
{
    public function __construct(
        protected FineService $service,
        protected FineRepositoryInterface $repository
    ) {}

    public function index(Request $request)
    {
        return FineResource::collection(
            $this->repository->paginate($request)
        );
    }

    public function show(Fine $fine): FineResource
    {
        return new FineResource(
            $this->repository->findById($fine->id)
        );
    }

    public function pay(Fine $fine): FineResource
    {
        return new FineResource(
            $this->service->pay($fine)
        );
    }

    public function waive(Fine $fine): FineResource
    {
        return new FineResource(
            $this->service->waive($fine)
        );
    }
}
