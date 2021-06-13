<?php

namespace Tests\Feature\Admin\Users;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUsersTest extends TestCase
{
	use RefreshDatabase;
	use WithFaker;

	public function test_an_user_can_not_be_updated_without_permission()
	{
		$user = User::factory()->create();
		$user->attachRole('user');
		$userToUpdate = User::factory()->create();
		$userToUpdate->attachRole('user');
		$roleUser = Role::where('name', 'user')->first();
		$this->actingAs($user);
		$response = $this->json('PUT', route('admin.users.update', $userToUpdate), [
			'first_name' => $userToUpdate->first_name,
			'last_name' => $userToUpdate->last_name,
			'email' => $userToUpdate->email,
			'role_ids' => [$roleUser->id],
			'active' => $userToUpdate->active,
		]);

		$response->assertStatus(403);
	}
	public function test_an_user_can_not_be_updated_without_empty_fields()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');
		$userToUpdate = User::factory()->create();
		$userToUpdate->attachRole('user');
		$this->actingAs($user);
		$response = $this->json('PUT', route('admin.users.update', $userToUpdate), [
			'first_name' => '',
			'last_name' => '',
			'email' => '',
			'role_ids' => '',
			'active' => ''
		]);

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'first_name' => [__('validation.required', ['attribute' => 'first name'])],
				'last_name' => [__('validation.required', ['attribute' => 'last name'])],
				'email' => [__('validation.required', ['attribute' => 'email'])],
				'role_ids' => [__('validation.required', ['attribute' => 'roles'])],
				'active' => [__('validation.required', ['attribute' => 'status'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_update_first_name_must_be_an_string()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');
		$userToUpdate = User::factory()->create();
		$userToUpdate->attachRole('user');
		$roleUser = Role::where('name', 'user')->first();
		$this->actingAs($user);
		$response = $this->json('PUT', route('admin.users.update', $userToUpdate), [
			'first_name' => 123,
			'last_name' => $userToUpdate->last_name,
			'email' => $userToUpdate->email,
			'role_ids' => [$roleUser->id],
			'active' => $userToUpdate->active,
		]);

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'first_name' => [__('validation.string', ['attribute' => 'first name'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_update_first_name_must_contain_50_characters_max()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');
		$userToUpdate = User::factory()->create();
		$userToUpdate->attachRole('user');
		$roleUser = Role::where('name', 'user')->first();
		$this->actingAs($user);
		$response = $this->json('PUT', route('admin.users.update', $userToUpdate), [
			'first_name' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
			'last_name' => $userToUpdate->last_name,
			'email' => $userToUpdate->email,
			'role_ids' => [$roleUser->id],
			'active' => $userToUpdate->active,
		]);

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'first_name' => [__('validation.max.string', ['attribute' => 'first name', 'max' => 50])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_update_last_name_must_be_an_string()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');
		$userToUpdate = User::factory()->create();
		$userToUpdate->attachRole('user');
		$roleUser = Role::where('name', 'user')->first();
		$this->actingAs($user);
		$response = $this->json('PUT', route('admin.users.update', $userToUpdate), [
			'first_name' =>	$userToUpdate->first_name,
			'last_name' => 123,
			'email' => $userToUpdate->email,
			'role_ids' => [$roleUser->id],
			'active' => $userToUpdate->active,
		]);

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'last_name' => [__('validation.string', ['attribute' => 'last name'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_update_last_name_must_contain_50_characters_max()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');
		$userToUpdate = User::factory()->create();
		$userToUpdate->attachRole('user');
		$roleUser = Role::where('name', 'user')->first();
		$this->actingAs($user);
		$response = $this->json('PUT', route('admin.users.update', $userToUpdate), [
			'first_name' => $userToUpdate->first_name,
			'last_name' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
			'email' => $userToUpdate->email,
			'role_ids' => [$roleUser->id],
			'active' => $userToUpdate->active,
		]);

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'last_name' => [__('validation.max.string', ['attribute' => 'last name', 'max' => 50])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_update_email_must_contain_50_characters_max()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');
		$userToUpdate = User::factory()->create();
		$userToUpdate->attachRole('user');
		$roleUser = Role::where('name', 'user')->first();
		$this->actingAs($user);
		$response = $this->json('PUT', route('admin.users.update', $userToUpdate), [
			'first_name' => $userToUpdate->first_name,
			'last_name' => $userToUpdate->last_name,
			'email' => 'aaaaaaaaaaaaaaaa@aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.aaa',
			'role_ids' => [$roleUser->id],
			'active' => $userToUpdate->active,
		]);

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'email' => [__('validation.max.string', ['attribute' => 'email', 'max' => 50])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_update_email_must_be_an_valid_email()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');
		$userToUpdate = User::factory()->create();
		$userToUpdate->attachRole('user');
		$roleUser = Role::where('name', 'user')->first();
		$this->actingAs($user);
		$response = $this->json('PUT', route('admin.users.update', $userToUpdate), [
			'first_name' => $userToUpdate->first_name,
			'last_name' => $userToUpdate->last_name,
			'email' => 'abc',
			'role_ids' => [$roleUser->id],
			'active' => $userToUpdate->active,
		]);

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'email' => [__('validation.email', ['attribute' => 'email'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_update_email_must_be_unique()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');
		$userToUpdate = User::factory()->create();
		$userToUpdate->attachRole('user');
		$roleUser = Role::where('name', 'user')->first();
		$this->actingAs($user);
		$response = $this->json('PUT', route('admin.users.update', $userToUpdate), [
			'first_name' => $userToUpdate->first_name,
			'last_name' => $userToUpdate->last_name,
			'email' => $user->email,
			'role_ids' => [$roleUser->id],
			'active' => $userToUpdate->active,
		]);

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'email' => [__('validation.unique', ['attribute' => 'email'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_update_role_id_must_be_an_array()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');
		$userToUpdate = User::factory()->create();
		$userToUpdate->attachRole('user');
		$this->actingAs($user);
		$response = $this->json('PUT', route('admin.users.update', $userToUpdate), [
			'first_name' => $userToUpdate->first_name,
			'last_name' => $userToUpdate->last_name,
			'email' => $userToUpdate->email,
			'role_ids' => 'abc',
			'active' => $userToUpdate->active,
		]);

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'role_ids' => [__('validation.array', ['attribute' => 'roles'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_update_role_ids_must_exists()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');
		$userToUpdate = User::factory()->create();
		$userToUpdate->attachRole('user');
		$this->actingAs($user);
		$response = $this->json('PUT', route('admin.users.update', $userToUpdate), [
			'first_name' => $userToUpdate->first_name,
			'last_name' => $userToUpdate->last_name,
			'email' => $userToUpdate->email,
			'role_ids' => [99, 55],
			'active' => $userToUpdate->active,
		]);

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'role_ids' => [__('validation.exists', ['attribute' => 'roles'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_update_active_must_be_a_boolean_value()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');
		$userToUpdate = User::factory()->create();
		$userToUpdate->attachRole('user');
		$roleUser = Role::where('name', 'user')->first();
		$this->actingAs($user);
		$response = $this->json('PUT', route('admin.users.update', $userToUpdate), [
			'first_name' => $userToUpdate->first_name,
			'last_name' => $userToUpdate->last_name,
			'email' => $userToUpdate->email,
			'role_ids' => [$roleUser->id],
			'active' => 'abc',
		]);

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'active' => [__('validation.boolean', ['attribute' => 'status'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_a_user_can_be_updated()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');
		$userToUpdate = User::factory()->create();
		$userToUpdate->attachRole('user');
		$roleAdmin = Role::where('name', 'admin')->first();
		$this->actingAs($user);
		$response = $this->json('PUT', route('admin.users.update', $userToUpdate), $updatedUser = [
			'first_name' => $this->faker->firstName,
			'last_name' => $this->faker->lastName,
			'email' => $this->faker->unique()->safeEmail,
			'role_ids' => [$roleAdmin->id],
			'active' => 0,
		]);

		$response->assertStatus(200);
		$response->assertExactJson([
			'record' => [
				'id' => $userToUpdate->id,
				'first_name' => $updatedUser['first_name'],
				'last_name' => $updatedUser['last_name'],
				'email' => $updatedUser['email'],
				'roles' => [$roleAdmin->only(['id', 'name', 'display_name'])],
				'active' => $updatedUser['active'],
				'avatar' => $userToUpdate->fresh()->profile_photo_url,
				'destroyURL' => route('admin.users.destroy', $userToUpdate->id),
			],
			'message' => 'The user was updated successfully.',
		]);
		$this->assertTrue($userToUpdate->hasRole($roleAdmin->name));
	}

	public function test_a_user_can_be_updated_without_changing_the_email()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');
		$userToUpdate = User::factory()->create();
		$userToUpdate->attachRole('user');
		$roleAdmin = Role::where('name', 'admin')->first();
		$this->actingAs($user);
		$response = $this->json('PUT', route('admin.users.update', $userToUpdate), $updatedUser = [
			'first_name' => $this->faker->firstName,
			'last_name' => $this->faker->lastName,
			'email' => $userToUpdate->email,
			'role_ids' => [$roleAdmin->id],
			'active' => 1,
		]);

		$response->assertStatus(200);
		$response->assertExactJson([
			'record' => [
				'id' => $userToUpdate->id,
				'first_name' => $updatedUser['first_name'],
				'last_name' => $updatedUser['last_name'],
				'email' => $userToUpdate->email,
				'roles' => [$roleAdmin->only(['id', 'name', 'display_name'])],
				'active' => $updatedUser['active'],
				'avatar' => $userToUpdate->fresh()->profile_photo_url,
				'destroyURL' => route('admin.users.destroy', $userToUpdate->id),
			],
			'message' => 'The user was updated successfully.',
		]);
		$this->assertTrue($userToUpdate->hasRole($roleAdmin->name));
	}
}
