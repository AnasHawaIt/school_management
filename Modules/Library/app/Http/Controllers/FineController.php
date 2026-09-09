<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Library\Entities\Fine;
use Modules\Library\app\Http\Requests\UpdateFineRequest;
use Modules\Library\app\Http\Resources\FineResource;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $fines = Fine::query()
            ->with('transaction.member.user')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->latest()
            ->paginate(min((int) $request->get('per_page', 10), 100));

        return FineResource::collection($fines);
    }

    public function show(Fine $fine): FineResource
    {
        return new FineResource($fine->load('transaction.member.user'));
    }

    public function update(UpdateFineRequest $request, Fine $fine)
    {
        if ($fine->status !== 'unpaid') {
            return response()->json([
                'message' => 'Only unpaid fines can be settled.',
            ], 422);
        }

        $data = $request->validated();
        $data[$data['status'] === 'paid' ? 'paid_at' : 'waived_at'] = now();
        $fine->update($data);

        return new FineResource($fine->refresh());
    }
}
