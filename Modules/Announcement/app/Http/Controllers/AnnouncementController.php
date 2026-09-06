<?php

namespace Modules\Announcement\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Announcement\app\Resources\AnnouncementResource;
use Modules\Announcement\Services\AnnouncementService;
use Modules\Announcement\app\Requests\AnnouncementCreateRequest;
use Modules\Announcement\app\Requests\AnnouncementUpdateRequest;

class AnnouncementController extends Controller
{
    protected AnnouncementService $service;

    public function __construct(AnnouncementService $service)
    {
        $this->service = $service;

    }

    public function restore($id)
    {
        return new AnnouncementResource( $this->service->restore($id));
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
        return AnnouncementResource::collection(
            $this->service->getAnnouncementOnlyTrashed()
        );

    }

    public function indexPublished()
    {
        return $this->service->getPublishedAnnouncements();
    }

    public function index()
    {
        $announcements = cache()->remember('announcements_all', 300, function () {

            return $this->service->getAll();

        });

        return AnnouncementResource::collection($announcements);
    }

    public function store(AnnouncementCreateRequest $request)
    {
        $data =[
            'title' => $request->title,
            'user_id' => $request->user_id,
            'is_active' =>true,
            'body' => $request->body,
            'audience'=>$request->audience,
            'published_at' => $request->published_at,
            'expires_at' => $request->expires_at,
            'type' => $request->type,
        ];

        $images = $request->file('images');

        $announcement = $this->service->create($data,$images);

        return new AnnouncementResource($announcement);
    }

    public function update(AnnouncementUpdateRequest $request)
    {
        $data =[
            'title' => $request->title,
            'user_id' => $request->user_id,
            'is_active' => true,
            'body' => $request->body,
            'audience'=>$request->audience,
            'published_at' => $request->published_at,
            'expires_at' => $request->expires_at,
            'type' => $request->type,
        ];

        $images = $request->file('images');

        $announcement = $this->service->update($request->id, $data,$images);

        return new AnnouncementResource($announcement);
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json(['success', 'Message deleted!'],);
    }
}
