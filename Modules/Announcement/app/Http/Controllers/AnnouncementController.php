<?php

namespace Modules\Announcement\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Announcement\app\Resources\AnnouncementResource;
use Modules\Announcement\Services\AnnouncementService;
use Modules\Announcement\app\Requests\AnnouncementCreateRequest;
use Modules\Announcement\app\Requests\AnnouncementUpdateRequest;
use Modules\Announcement\app\Requests\AnnouncementDeleteRequest;

class AnnouncementController extends Controller
{
    protected AnnouncementService $service;

    public function __construct(AnnouncementService $service)
    {
        $this->service = $service;

        // يمكنك تطبيق middleware هنا
        // مثال: $this->middleware('auth');
    }

    public function index()
    {
        $announcements = cache()->remember('announcements_all', 300, function () {
            return $this->service->getAll();
        });

        return AnnouncementResource::collection(['announcement::index', compact('announcements')]);
    }

    public function store(AnnouncementCreateRequest $request)
    {
        $announcement = $this->service->create($request->validated());

        return AnnouncementResource::collection(['Announcement'=>$announcement, 'Announcement created success']);
    }

    public function update(AnnouncementUpdateRequest $request)
    {
        $announcement = $this->service->update($request->id, $request->validated());

        return AnnouncementResource::collection(['Announcement'=>$announcement, 'Announcement updated success'],);
    }

    public function destroy(AnnouncementDeleteRequest $request)
    {
        $this->service->delete($request->id);

        return response()->json(['success', 'Announcement deleted!'],);
    }
}
