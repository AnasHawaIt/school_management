<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Library\app\Http\Requests\StoreMemberRequest;
use Modules\Library\app\Http\Requests\UpdateMemberRequest;
use Modules\Library\app\Http\Resources\MemberResource;
use Modules\Library\Entities\Member;
use Modules\Library\Services\MemberService;

class MemberController extends Controller
{
    protected $service;

    public function __construct(MemberService $service)
    {
        $this->service = $service;
    }

    public function restore($id)
    {
        return new MemberResource( $this->service->restore($id));
    }

    public function forceDelete($id)
    {
        $this->service->forceDelete($id);

        return response()->json([
            'message' => 'Force deleted successfully'
        ]);
    }

    public function AllOnlyTrashed()
    {
        return MemberResource::collection(
            $this->service->getMemberOnlyTrashed()
        );
    }

    public function index(Request $request)
    {
        return new MemberResource(Member::with('transactions')->get());
    }

    public function store(StoreMemberRequest $request)
    {
        return new MemberResource($this->service->create($request->all()));
    }

    public function show($id)
    {
        return new MemberResource($this->service->findById($id));
    }

    public function update(UpdateMemberRequest $request, $id)
    {
        return new MemberResource($this->service->update($id, $request->all()));
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}
