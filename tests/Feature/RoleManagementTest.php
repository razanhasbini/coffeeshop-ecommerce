<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_create_a_manager(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->forceFill(['role' => 'super_admin', 'is_admin' => true, 'is_active' => true])->save();

        $this->actingAs($superAdmin)->post(route('admin.managers.store'), [
            'username' => 'Store Manager',
            'email' => 'manager@example.com',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', ['email' => 'manager@example.com', 'role' => 'manager', 'is_active' => true]);
        $this->assertDatabaseHas('activity_logs', ['action' => 'manager.created']);
    }

    public function test_manager_cannot_create_another_manager(): void
    {
        $manager = User::factory()->create();
        $manager->forceFill(['role' => 'manager', 'is_admin' => true, 'is_active' => true])->save();

        $this->actingAs($manager)->post(route('admin.managers.store'), [
            'username' => 'Unauthorized Manager',
            'email' => 'blocked@example.com',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
        ])->assertForbidden();
    }

    public function test_suspended_user_cannot_sign_in(): void
    {
        $user = User::factory()->create();
        $user->forceFill(['is_active' => false])->save();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertDatabaseHas('activity_logs', ['action' => 'login.blocked']);
    }
}
