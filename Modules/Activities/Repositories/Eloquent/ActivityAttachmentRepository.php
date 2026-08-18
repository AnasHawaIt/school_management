<?php


namespace Modules\Activities\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Modules\Activities\Models\ActivityAttachment;
use Modules\Activities\Repositories\Interfaces\ActivityAttachmentRepositoryInterface;

class ActivityAttachmentRepository implements ActivityAttachmentRepositoryInterface
{
    public function __construct(
        protected ActivityAttachment $model
    )
    {
    }

    public function findById(int $id): ?ActivityAttachment
    {
        return $this->model
            ->with(['activity', 'uploader'])
            ->find($id);
    }

    public function findOrFail(int $id): ActivityAttachment
    {
        return $this->model
            ->with(['activity', 'uploader'])
            ->findOrFail($id);
    }

    public function getForActivity(
        int $activityId
    ): Collection
    {
        return $this->model
            ->where('activity_id', $activityId)
            ->latest()
            ->get();
    }

    public function create(
        array $data
    ): ActivityAttachment
    {
        return $this->model->create($data);
    }

    public function update(
        ActivityAttachment $attachment,
        array              $data
    ): ActivityAttachment
    {
        $attachment->update($data);

        return $attachment->refresh();
    }

    public function delete(
        ActivityAttachment $attachment
    ): bool
    {
        return (bool)$attachment->delete();
    }
}
