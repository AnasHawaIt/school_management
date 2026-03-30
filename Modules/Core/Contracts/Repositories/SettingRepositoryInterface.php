<?php

namespace Modules\Core\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;

interface SettingRepositoryInterface extends BaseRepositoryInterface
{
    public function getByKey(string $key);

    public function setValue(string $key, $value, string $type = 'string'): void;

    public function hasKey(string $key): bool;

    public function deleteByKey(string $key): void;
}
