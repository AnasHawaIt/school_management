<?php

namespace Modules\Transport\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Transport\Services\SubscriptionService;

class SubscriptionController extends Controller
{
    protected $service;

    public function __construct(SubscriptionService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $subscription = $this->service->create($request->all());

        return response()->json($subscription);
    }

    public function expire($id)
    {
        return response()->json(
            $this->service->expire($id)
        );
    }

}
