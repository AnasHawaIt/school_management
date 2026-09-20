<?php


namespace Modules\Activities\app\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Modules\Activities\app\Entities\ActivityAttachment;

interface ActivityAttachmentRepositoryInterface
{
    public function findById(int $id): ?ActivityAttachment;

    public function findOrFail(int $id): ActivityAttachment;

    public function getForActivity(
        int $activityId
    ): Collection;

    public function create(
        array $data
    ): ActivityAttachment;

    public function update(
        ActivityAttachment $attachment,
        array              $data
    ): ActivityAttachment;

    public function delete(
        ActivityAttachment $attachment
    ): bool;
}
