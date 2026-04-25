<?php

namespace Modules\Announcement\Repositories\Interfaces;

interface AnnouncementRepositoryInterface

{
    public function getAnnouncementOnlyTrashed();
    public function restore($id);
    public function forceDelete($id);
    public function getAll();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
