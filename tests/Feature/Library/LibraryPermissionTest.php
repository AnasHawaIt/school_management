<?php

namespace Tests\Feature\Library;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Entities\Permission;
use Modules\Core\Entities\Role;
use Tests\TestCase;

class LibraryPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_without_library_permission_is_forbidden(): void
    {
        $user = User::factory()->create(['user_type' => 'teacher']);

        $this->actingAs($user)
            ->getJson('/api/library/books')
            ->assertForbidden();
    }

    public function test_library_permission_allows_catalog_access_without_admin_status(): void
    {
        $user = User::factory()->create(['user_type' => 'teacher']);
        $permission = Permission::create([
            'name' => 'library.catalog.view',
            'display_name' => 'View Library Catalog',
        ]);
        $role = Role::create(['name' => 'librarian', 'display_name' => 'Librarian']);
        $role->permissions()->attach($permission);
        $user->assignRole('librarian');

        $this->actingAs($user)
            ->getJson('/api/library/books')
            ->assertOk();
    }
}
