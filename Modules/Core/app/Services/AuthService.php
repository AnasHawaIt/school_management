<?php

namespace Modules\Core\app\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\Core\app\Contracts\Repositories\UserRepositoryInterface;
use Modules\Core\app\Contracts\Services\AuthServiceInterface;
use Modules\Core\app\Entities\User;
use Modules\Core\app\Events\Auth\TokenRefreshed;
use Modules\Core\app\Events\Auth\UserLoggedIn;
use Modules\Core\app\Events\Auth\UserLoggedOut;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Login user.
     */
    public function login(array $credentials): array
    {
        $user = $this->userRepository->findBy(
            'email',
            $credentials['email']
        );

        if (
            !$user ||
            !Hash::check($credentials['password'], $user->password)
        ) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive.'],
            ]);
        }

        // Create authentication token
        $token = $user
            ->createToken('auth-token')
            ->plainTextToken;

        // Fire login event
        event(new UserLoggedIn(
            user: $user,
            userId: $user->id,
        ));

        return [
            'user' => $user->load('roles.permissions'),
            'token' => $token,
        ];
    }

    /**
     * Logout authenticated user.
     */
    public function logout(): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user) {
            throw ValidationException::withMessages([
                'auth' => ['Unauthenticated.'],
            ]);
        }

        // Delete current access token
        $currentToken = $user->currentAccessToken();

        if ($currentToken) {
            $currentToken->delete();
        }

        // Fire logout event
        event(new UserLoggedOut(
            user: $user,
            userId: $user->id,
        ));

        return true;
    }

    /**
     * Get authenticated user.
     */
    public function me(): User
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->load('roles.permissions');
    }

    /**
     * Refresh authentication token.
     */
    public function refreshToken(): string
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user) {
            throw ValidationException::withMessages([
                'auth' => ['Unauthenticated.'],
            ]);
        }

        // Delete current token
        $currentToken = $user->currentAccessToken();

        if ($currentToken) {
            $currentToken->delete();
        }

        // Create new token
        $token = $user
            ->createToken('auth-token')
            ->plainTextToken;

        // Fire refresh event
        event(new TokenRefreshed(
            user: $user,
            userId: $user->id,
        ));

        return $token;
    }
}
