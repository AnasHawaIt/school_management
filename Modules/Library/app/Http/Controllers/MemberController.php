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
        return new MemberResource($this->service->forceDelete($id));
    }

    public function AllOnlyTrashed()
    {
        return new MemberResource($this->service->getMemberOnlyTrashed());
    }

    public function index(Request $request)
    {
        $members = Member::with('transactions')->get();

        return new MemberResource($members);
    }

    public function store(StoreMemberRequest $request)
    {
        $member=$this->service->create($request->all());

        return new MemberResource($member);
    }

    public function show($id)
    {
        $member=$this->service->findById($id);

        return new MemberResource($member);
    }

    public function update(UpdateMemberRequest $request, $id)
    {
        $member=$this->service->update($id, $request->all());

        return new MemberResource($member);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}
