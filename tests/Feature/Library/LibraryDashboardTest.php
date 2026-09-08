<?php

namespace Tests\Feature\Library;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Entities\User;
use Tests\TestCase;

class LibraryDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_library_dashboard_is_available_to_an_authenticated_admin(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);

        $this->actingAs($user)
            ->get('/library/dashboard')
            ->assertOk()
            ->assertViewIs('library.dashboard')
            ->assertSee('Library dashboard');
    }

    public function test_library_dashboard_requires_library_catalog_permission(): void
    {
        $user = User::factory()->create(['user_type' => 'teacher']);

        $this->actingAs($user)
            ->get('/library/dashboard')
            ->assertForbidden();
    }
}
