<?php

namespace Tests\Feature\Library;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Entities\Permission;
use Modules\Core\Entities\Role;
use Modules\Library\Entities\Author;
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

    public function test_catalog_delete_requires_delete_permission(): void
    {
        $user = User::factory()->create(['user_type' => 'teacher']);
        $permission = Permission::create([
            'name' => 'library.catalog.manage',
            'display_name' => 'Manage Library Catalog',
        ]);
        $role = Role::create(['name' => 'catalog-manager', 'display_name' => 'Catalog Manager']);
        $role->permissions()->attach($permission);
        $user->assignRole('catalog-manager');
        $author = Author::create(['name' => 'Protected Author', 'birth_date' => '1980-01-01']);

        $this->actingAs($user)
            ->deleteJson("/api/library/authors/{$author->id}")
            ->assertForbidden();
    }

    public function test_restore_requires_restore_permission(): void
    {
        $user = User::factory()->create(['user_type' => 'teacher']);
        $permission = Permission::create([
            'name' => 'library.catalog.delete',
            'display_name' => 'Delete Library Catalog',
        ]);
        $role = Role::create(['name' => 'catalog-deleter', 'display_name' => 'Catalog Deleter']);
        $role->permissions()->attach($permission);
        $user->assignRole('catalog-deleter');

        $this->actingAs($user)
            ->postJson('/api/library/authors/1/restore')
            ->assertForbidden();
    }
}
