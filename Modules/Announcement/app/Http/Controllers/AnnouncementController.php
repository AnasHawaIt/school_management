<?php

namespace Modules\Announcement\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Announcement\app\Requests\AnnouncementCreateRequest;
use Modules\Announcement\app\Requests\AnnouncementScheduleRequest;
use Modules\Announcement\app\Requests\AnnouncementUpdateRequest;
use Modules\Announcement\app\Resources\AnnouncementResource;
use Modules\Announcement\Services\AnnouncementService;

class AnnouncementController extends Controller
{
    protected AnnouncementService $service;

    public function __construct(
        AnnouncementService $service,
        private \Modules\Announcement\Services\AnnouncementCache $cache
    ) {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $announcements = cache()->remember(
            $this->cache->key($request),
            300,
            fn () => $this->service->getAll($request)
        );

        return AnnouncementResource::collection($announcements);
    }

    public function indexPublished()
    {
        return AnnouncementResource::collection(
            $this->service->getPublishedAnnouncements()
        );
    }

    public function indexExpired()
    {
        return AnnouncementResource::collection(
            $this->service->getExpiredAnnouncements()
        );
    }

    public function indexPinned()
    {
        return AnnouncementResource::collection(
            $this->service->getPinnedAnnouncements()
        );
    }

    public function indexScheduled()
    {
        return AnnouncementResource::collection(
            $this->service->getScheduledAnnouncements()
        );
    }

    public function store(AnnouncementCreateRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = $request->user()->id;

        $images = $request->file('images');

        $announcement = $this->service->create(
            $data,
            $images
        );

        $this->cache->invalidate();

        return new AnnouncementResource($announcement);
    }

    public function show(int $id)
    {
        return new AnnouncementResource(
            $this->service->find($id)
        );
    }

    public function update(AnnouncementUpdateRequest $request, int $id) {
        $data = $request->validated();

        unset($data['created_by']);

        $images = $request->file('images');

        $announcement = $this->service->update(
            $id,
            $data,
            $images
        );

        $this->cache->invalidate();

        return new AnnouncementResource($announcement);
    }

    public function publish(int $id)
    {
        $announcement = $this->service->publish($id);

        $this->cache->invalidate();

        return new AnnouncementResource($announcement);
    }

    public function pin(int $id)
    {
        $announcement = $this->service->pin($id);

        $this->cache->invalidate();

        return new AnnouncementResource($announcement);
    }

    public function unpin(int $id)
    {
        $announcement = $this->service->unpin($id);

        $this->cache->invalidate();

        return new AnnouncementResource($announcement);
    }

    public function cancel(int $id)
    {
        $announcement = $this->service->cancel($id);

        $this->cache->invalidate();

        return new AnnouncementResource($announcement);
    }

    public function schedule(AnnouncementScheduleRequest $request, int $id) {
        $announcement = $this->service->schedule(
            $id,
            $request->date('scheduled_at')
        );

        $this->cache->invalidate();

        return new AnnouncementResource($announcement);
    }

    public function expire(int $id)
    {
        $announcement = $this->service->expire($id);

        $this->cache->invalidate();

        return new AnnouncementResource($announcement);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        $this->cache->invalidate();

        return response()->json([
            'message' => 'Announcement deleted successfully.',
        ]);
    }

    public function onlyTrashed()
    {
        return AnnouncementResource::collection(
            $this->service->getAnnouncementOnlyTrashed()
        );
    }

    public function restore(int $id)
    {
        $announcement = $this->service->restore($id);

        $this->cache->invalidate();

        return new AnnouncementResource($announcement);
    }

    public function forceDelete(int $id): JsonResponse
    {
        $this->service->forceDelete($id);

        return response()->json([
            'message' => 'Announcement permanently deleted.',
        ]);
    }
}
