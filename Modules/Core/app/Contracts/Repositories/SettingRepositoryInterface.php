<?php

namespace Modules\Core\app\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Modules\Core\app\Entities\Setting;

interface SettingRepositoryInterface extends BaseRepositoryInterface
{
    public function getByKey(string $key): ?Setting;

    public function setValue(
        string $key,
               $value,
        string $type = 'string'
    ): Setting;

    public function hasKey(string $key): bool;

    public function deleteByKey(string $key): void;
}
