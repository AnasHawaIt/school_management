<?php

namespace Modules\Core\Contracts\Repositories;

use Modules\Shared\Contracts\Repositories\BaseRepositoryInterface;
use Modules\Core\Entities\Setting;

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
