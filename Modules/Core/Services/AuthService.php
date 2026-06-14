<?php

namespace Modules\Core\Services;

use Modules\Core\Contracts\Repositories\UserRepositoryInterface;
use Modules\Core\Contracts\Repositories\ActivityLogRepositoryInterface;
use Modules\Core\Contracts\Services\AuthServiceInterface;
use Modules\Core\Entities\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    protected $userRepository;
    protected $activityLogRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        ActivityLogRepositoryInterface $activityLogRepository
    ) {
        $this->userRepository = $userRepository;
        $this->activityLogRepository = $activityLogRepository;
    }

    public function login(array $credentials): array
    {
        $user = $this->userRepository->findBy('email', $credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive.'],
            ]);
        }

        // Generate token
        $token = $user->createToken('auth-token')->plainTextToken;

        // Log activity
        $this->activityLogRepository->log([
            'action' => 'login',
            'model_type' => User::class,
           // 'model_id' => $user->id,
        ]);

        return [
            'user' => $user->load('roles'),
            'token' => $token,
        ];
    }

    public function logout(): bool
    {
        $user = auth()->user();

        // Log activity
        $this->activityLogRepository->log([
            'action' => 'logout',
            'model_type' => User::class,
        //    'model_id' => $user->id,
        ]);

        // Delete current token
        $user->currentAccessToken()->delete();

        return true;
    }

    public function me(): User
    {
        return auth()->user()->load('roles.permissions');
    }




    public function refreshToken(): string
    {
        $user = auth()->user();

        // Delete old token
        $user->currentAccessToken()->delete();

        // Create new token
        return $user->createToken('auth-token')->plainTextToken;
    }
}
