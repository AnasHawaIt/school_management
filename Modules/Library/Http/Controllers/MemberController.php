<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Library\Entities\Member;
use Modules\Library\Http\Requests\StoreMemberRequest;
use Modules\Library\Http\Requests\UpdateMemberRequest;
use Modules\Library\Http\Resources\MemberResource;
use Modules\Library\Repositories\Interfaces\MemberRepositoryInterface;

class MemberController extends Controller
{
    protected $memberRepo;

    public function __construct(MemberRepositoryInterface $memberRepo)
    {
        $this->memberRepo = $memberRepo;
    }

    public function index(Request $request)
    {
        $members = Member::with('transactions')->get($request);

        return MemberResource::collection($members);
    }

    public function store(StoreMemberRequest $request)
    {
        $member=$this->memberRepo->create($request->all());

        return new MemberResource($member);
    }

    public function show($id)
    {
        $member=$this->memberRepo->findById($id);

        return new MemberResource($member);
    }

    public function update(UpdateMemberRequest $request, $id)
    {
        $member=$this->memberRepo->update($id, $request->all());

        return new MemberResource($member);
    }

    public function destroy($id)
    {
        $this->memberRepo->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}
