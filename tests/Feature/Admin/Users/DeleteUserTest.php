<?php

namespace Tests\Feature\Admin\Users;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_not_be_deleted_without_permission()
    {
        $user = User::factory()->create();
        $userToDelete = User::factory()->create();
        $user->attachRole('user');
        $userToDelete->attachRole('user');
        $this->actingAs($user);
        $response = $this->json('DELETE', route('admin.users.destroy', $userToDelete));

        $response->assertStatus(403);
    }

    public function test_a_user_can_delete_himself()
    {
        $user = User::factory()->create();
        $user->attachRole('admin');
        $this->actingAs($user);
        $response = $this->json('DELETE', route('admin.users.destroy', $user));

        $response->assertStatus(403);
        $response->assertExactJson([
            'message' => 'You can\'t delete yourself dummy!'
        ]);
    }

    public function test_a_user_can_be_deleted_with_permission()
    {
        $user = User::factory()->create();
        $userToDelete = User::factory()->create();
        $user->attachRole('admin');
        $userToDelete->attachRole('user');
        $this->actingAs($user);

        $this->assertCount(2, User::all());
        $response = $this->json('DELETE', route('admin.users.destroy', $userToDelete));

        $response->assertStatus(200);
        $this->assertCount(1, User::all());
        $this->assertNull($userToDelete->fresh());
        $response->assertExactJson([
            'message' => 'The user was deleted successfully.'
        ]);
    }
}
