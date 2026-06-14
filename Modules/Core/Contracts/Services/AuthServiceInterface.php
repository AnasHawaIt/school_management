<?php

namespace Modules\Core\Contracts\Services;

use Modules\Core\Entities\User;

interface AuthServiceInterface
{
    public function login(array $credentials): array;

    public function logout(): bool;

    public function me(): User;


    public function refreshToken(): string;
}
