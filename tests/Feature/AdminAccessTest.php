<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_access_admin_routes(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function non_admin_users_cannot_access_admin_routes(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
        $response->assertSee('Unauthorized. Admin access required.');
    }

    /** @test */
    public function admin_users_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_users_can_access_categories_index(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/categories');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_users_can_access_tools_index(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/tools');

        $response->assertStatus(200);
    }

    /** @test */
    public function non_admin_users_cannot_access_category_management(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get('/admin/categories')
            ->assertStatus(403);

        $this->actingAs($user)
            ->get('/admin/categories/create')
            ->assertStatus(403);
    }

    /** @test */
    public function non_admin_users_cannot_access_tool_management(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get('/admin/tools')
            ->assertStatus(403);

        $this->actingAs($user)
            ->get('/admin/tools/create')
            ->assertStatus(403);
    }

    /** @test */
    public function non_admin_users_cannot_access_data_management(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get('/admin/data');

        $response->assertStatus(403);
    }

    /** @test */
    public function user_is_admin_method_works_correctly(): void
    {
        $regularUser = User::factory()->create([
            'is_admin' => false,
        ]);

        $adminUser = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->assertFalse($regularUser->isAdmin());
        $this->assertTrue($adminUser->isAdmin());
    }

    /** @test */
    public function is_admin_attribute_defaults_to_false(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->is_admin);
        $this->assertFalse($user->isAdmin());
    }

    /** @test */
    public function admin_create_command_promotes_existing_user(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'is_admin' => false,
        ]);

        $this->assertFalse($user->isAdmin());

        $this->artisan('admin:create', [
            '--email' => 'test@example.com',
        ])->assertExitCode(0);

        $user->refresh();

        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function admin_create_command_creates_new_admin_user(): void
    {
        $this->artisan('admin:create', [
            '--email' => 'newadmin@example.com',
            '--name' => 'New Admin',
            '--password' => 'password123',
        ])->assertExitCode(0);

        $user = User::where('email', 'newadmin@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue($user->isAdmin());
        $this->assertEquals('New Admin', $user->name);
        $this->assertNotNull($user->email_verified_at);
    }

    /** @test */
    public function admin_create_command_handles_existing_admin_gracefully(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        $this->artisan('admin:create', [
            '--email' => 'admin@example.com',
        ])->assertExitCode(0)
          ->expectsOutput('User admin@example.com is already an admin.');

        $this->assertTrue($admin->fresh()->isAdmin());
    }

    /** @test */
    public function admin_create_command_validates_email(): void
    {
        $this->artisan('admin:create', [
            '--email' => 'invalid-email',
            '--name' => 'Test',
            '--password' => 'password123',
        ])->assertExitCode(1)
          ->expectsOutput('Invalid email address.');
    }
}
