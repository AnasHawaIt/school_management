<?php

namespace Tests\Feature\Library;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Entities\Permission;
use Modules\Core\Entities\Role;
use Modules\Library\Entities\Author;
use Modules\Core\database\seeders\PermissionSeeder;
use Modules\Core\database\seeders\RoleSeeder;
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

    public function test_force_delete_requires_force_delete_permission(): void
    {
        $user = User::factory()->create(['user_type' => 'teacher']);
        $permission = Permission::create([
            'name' => 'library.catalog.restore',
            'display_name' => 'Restore Library Catalog',
        ]);
        $role = Role::create(['name' => 'catalog-restorer', 'display_name' => 'Catalog Restorer']);
        $role->permissions()->attach($permission);
        $user->assignRole('catalog-restorer');
        $author = Author::create(['name' => 'Deleted Author', 'birth_date' => '1980-01-01']);
        $author->delete();

        $this->actingAs($user)
            ->deleteJson("/api/library/authors/{$author->id}/force")
            ->assertForbidden();
    }

    public function test_force_delete_is_allowed_only_with_force_delete_permission(): void
    {
        $user = User::factory()->create(['user_type' => 'teacher']);
        $permission = Permission::create([
            'name' => 'library.catalog.force_delete',
            'display_name' => 'Permanently Delete Library Catalog',
        ]);
        $role = Role::create(['name' => 'catalog-forcer', 'display_name' => 'Catalog Forcer']);
        $role->permissions()->attach($permission);
        $user->assignRole('catalog-forcer');
        $author = Author::create(['name' => 'Permanently Deleted Author', 'birth_date' => '1980-01-01']);
        $author->delete();

        $this->actingAs($user)
            ->deleteJson("/api/library/authors/{$author->id}/force")
            ->assertOk();

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }

    public function test_fresh_seeders_provide_all_library_permissions_to_admin(): void
    {
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);

        $libraryPermissions = [
            'library.catalog.view',
            'library.catalog.manage',
            'library.catalog.delete',
            'library.catalog.restore',
            'library.catalog.force_delete',
            'library.circulation.view',
            'library.circulation.manage',
            'library.circulation.restore',
            'library.circulation.force_delete',
            'library.fines.view',
            'library.fines.manage',
        ];

        $this->assertEqualsCanonicalizing(
            $libraryPermissions,
            \Modules\Core\Entities\Permission::query()
                ->whereIn('name', $libraryPermissions)
                ->pluck('name')
                ->all()
        );

        $admin = \Modules\Core\Entities\Role::where('name', 'admin')->firstOrFail();
        $this->assertTrue($admin->permissions()->whereIn('name', $libraryPermissions)->count() === count($libraryPermissions));
    }
}
