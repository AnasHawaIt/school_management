<?php

namespace Modules\Announcement\Repositories\Interfaces;

use Modules\Announcement\Entities\Announcement;

interface AnnouncementRepositoryInterface

{
    public function getAnnouncementOnlyTrashed();
    public function restore($id);
    public function forceDelete($id);
    public function getPublished();
    public function getScheduled();
    public function getExpired();
    public function getPinned();
    public function getAll();
    public function find(int $id): ?Announcement;
    public function create(array $data);
    public function update(int $id, array $data): Announcement;
    public function delete(int $id): bool;
}
