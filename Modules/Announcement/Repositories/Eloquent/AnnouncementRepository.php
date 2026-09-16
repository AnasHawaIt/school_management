<?php

namespace Modules\Announcement\Repositories\Eloquent;

use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Repositories\Interfaces\AnnouncementRepositoryInterface;
use Illuminate\Http\Request;

class AnnouncementRepository implements AnnouncementRepositoryInterface
{
    public function getAnnouncementOnlyTrashed()
    {
        return Announcement::onlyTrashed()->paginate(10);
    }

    public function restore($id)
    {
        $announcement = Announcement::withTrashed()->findOrFail($id);
        $announcement->restore();

        return $announcement;
    }

    public function forceDelete($id)
    {
        $announcement = Announcement::withTrashed()->findOrFail($id);

        $announcement->forceDelete();

        return $announcement;
    }

    public function getPublished()
    {
        return Announcement::query()
            ->published()
            ->get();
    }

    public function getScheduled()
    {
        return Announcement::query()
            ->scheduled()
            ->get();
    }

    public function getExpired()
    {
        return Announcement::query()
            ->expired()
            ->get();
    }

    public function getPinned()
    {
        return Announcement::query()
            ->Pinned()
            ->get();
    }

    public function getAll(Request $request)
    {
        $query = Announcement::query()
            ->with('creator')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')->value))
            ->when($request->filled('pinned'), fn ($q) => $q->where('is_pinned', $request->boolean('pinned')))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $term = $request->string('search')->value;
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('body', 'like', "%{$term}%");
            }));

        $sort = in_array($request->get('sort'), ['created_at', 'published_at', 'expires_at', 'priority'], true)
            ? $request->get('sort')
            : 'created_at';
        $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

        return $query
            ->orderBy($sort, $direction)
            ->paginate(min(max((int) $request->get('per_page', 10), 1), 100));
    }

    public function find(int $id): ?Announcement
    {
        return Announcement::find($id);
    }

    public function create(array $data)
    {
        return Announcement::create($data);
    }

    public function update(int $id, array $data): Announcement
    {
        $announcement = Announcement::findOrFail($id);

        $announcement->update($data);

        return $announcement->refresh();
    }

    public function delete(int $id): bool
    {
        $announcement = Announcement::findOrFail($id);

        return $announcement->delete();
    }
}
