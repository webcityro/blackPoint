<?php

namespace Tests\Feature\Admin\Users;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Tests\Traits\WithUserGenerator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUsersTest extends TestCase
{
	use RefreshDatabase;
	use WithFaker;
	use WithUserGenerator;

	public function test_user_index_page_can_be_accessed_with_permission()
	{
		$user = User::factory()->create()->attachRole('admin');
		$this->actingAs($user);
		$response = $this->get(route('admin.users.index'));

		$response->assertStatus(200);
	}

	public function test_user_index_page_can_not_be_accessed_without_permission()
	{
		$user = User::factory()->create();
		$user->attachRole('user');
		$this->actingAs($user);
		$response = $this->get(route('admin.users.store'));

		$response->assertStatus(403);
	}

	public function test_an_user_can_not_be_created_without_permission()
	{
		$user = User::factory()->create();
		$user->attachRole('user');
		$this->actingAs($user);
		$response = $this->post(route('admin.users.store'), $this->generateUser());

		$response->assertStatus(403);
	}

	public function test_an_user_can_not_be_created_without_empty_fields()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');
		$this->actingAs($user);
		$response = $this->json('POST', route('admin.users.store'), [
			'first_name' => '',
			'last_name' => '',
			'email' => '',
			'password' => '',
			'role_ids' => '',
			'active' => ''
		]);

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'first_name' => [__('validation.required', ['attribute' => 'first name'])],
				'last_name' => [__('validation.required', ['attribute' => 'last name'])],
				'email' => [__('validation.required', ['attribute' => 'email'])],
				'password' => [__('validation.required', ['attribute' => 'password'])],
				'role_ids' => [__('validation.required', ['attribute' => 'roles'])],
				'active' => [__('validation.required', ['attribute' => 'status'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_create_user_first_name_must_be_an_string()
	{
		$user = User::factory()->create();
		$role = Role::where('name', 'user')->first();
		$user->attachRole('admin');

		$this->actingAs($user);
		$response = $this->json('POST', route('admin.users.store'), $this->generateUser([
			'first_name' => 123,
			'role_ids' => [$role->id],
		]));

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'first_name' => [__('validation.string', ['attribute' => 'first name'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_create_user_first_name_must_contain_50_characters_max()
	{
		$user = User::factory()->create();
		$role = Role::where('name', 'user')->first();
		$user->attachRole('admin');

		$this->actingAs($user);
		$response = $this->json(
			'POST',
			route('admin.users.store'),
			$this->generateUser([
				'first_name' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
				'role_ids' => [$role->id],
			])
		);

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'first_name' => [__('validation.max.string', ['attribute' => 'first name', 'max' => 50])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_create_user_last_name_must_be_an_string()
	{
		$user = User::factory()->create();
		$role = Role::where('name', 'user')->first();
		$user->attachRole('admin');

		$this->actingAs($user);
		$response = $this->json('POST', route('admin.users.store'), $this->generateUser([
			'last_name' => 123,
			'role_ids' => [$role->id],
		]));

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'last_name' => [__('validation.string', ['attribute' => 'last name'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_create_user_last_name_must_contain_50_characters_max()
	{
		$user = User::factory()->create();
		$role = Role::where('name', 'user')->first();
		$user->attachRole('admin');

		$this->actingAs($user);
		$response = $this->json(
			'POST',
			route('admin.users.store'),
			$this->generateUser([
				'last_name' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
				'role_ids' => [$role->id],
			])
		);

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'last_name' => [__('validation.max.string', ['attribute' => 'last name', 'max' => 50])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_create_user_email_must_be_an_string()
	{
		$user = User::factory()->create();
		$role = Role::where('name', 'user')->first();
		$user->attachRole('admin');

		$this->actingAs($user);
		$response = $this->json('POST', route('admin.users.store'), $this->generateUser([
			'email' => 123,
			'role_ids' => [$role->id],
		]));

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'email' => [
					__('validation.string', ['attribute' => 'email']),
					__('validation.email', ['attribute' => 'email']),
				],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_create_user_email_must_contain_50_characters_max()
	{
		$user = User::factory()->create();
		$role = Role::where('name', 'user')->first();
		$user->attachRole('admin');

		$this->actingAs($user);
		$response = $this->json('POST', route('admin.users.store'), $this->generateUser([
			'email' => 'aaaaaaaaaaaaaaaa@aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.aaa',
			'role_ids' => [$role->id],
		]));

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'email' => [__('validation.max.string', ['attribute' => 'email', 'max' => 50])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_create_user_email_must_be_an_valid_email()
	{
		$user = User::factory()->create();
		$role = Role::where('name', 'user')->first();
		$user->attachRole('admin');

		$this->actingAs($user);
		$response = $this->json('POST', route('admin.users.store'), $this->generateUser([
			'email' => 'abc',
			'role_ids' => [$role->id],
		]));

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'email' => [__('validation.email', ['attribute' => 'email'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_create_user_email_must_be_unique()
	{
		$user = User::factory([
			'email' => 'jondoe@app.com'
		])->create();
		$role = Role::where('name', 'user')->first();
		$user->attachRole('admin');

		$this->actingAs($user);
		$response = $this->json('POST', route('admin.users.store'), $this->generateUser([
			'email' => 'jondoe@app.com',
			'role_ids' => [$role->id],
		]));

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'email' => [__('validation.unique', ['attribute' => 'email'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_create_user_password_must_be_an_string()
	{
		$user = User::factory()->create();
		$role = Role::where('name', 'user')->first();
		$user->attachRole('admin');

		$this->actingAs($user);
		$response = $this->json('POST', route('admin.users.store'), $this->generateUser([
			'password' => 123,
			'role_ids' => [$role->id],
		]));

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'password' => [
					__('validation.string', ['attribute' => 'password']),
					__('validation.min.string', ['attribute' => 'password', 'min' => 8]),
				],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_create_user_password_must_be_8_characters_min()
	{
		$user = User::factory()->create();
		$role = Role::where('name', 'user')->first();
		$user->attachRole('admin');

		$this->actingAs($user);
		$response = $this->json('POST', route('admin.users.store'), $this->generateUser([
			'password' => 'abc1234',
			'role_ids' => [$role->id],
		]));

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'password' => [__('validation.min.string', ['attribute' => 'password', 'min' => 8])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_create_user_role_ids_must_be_an_array()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');

		$this->actingAs($user);
		$response = $this->json('POST', route('admin.users.store'), $this->generateUser([
			'role_ids' => 'abc',
		]));

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'role_ids' => [__('validation.array', ['attribute' => 'roles'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_create_user_role_ids_must_exists()
	{
		$user = User::factory()->create();
		$user->attachRole('admin');

		$this->actingAs($user);
		$response = $this->json('POST', route('admin.users.store'), $this->generateUser([
			'role_ids' => [99, 55],
		]));

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'role_ids' => [__('validation.exists', ['attribute' => 'roles'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_create_user_active_must_be_a_boolean()
	{
		$user = User::factory()->create();
		$role = Role::where('name', 'user')->first();
		$user->attachRole('admin');

		$this->actingAs($user);
		$response = $this->json('POST', route('admin.users.store'), $this->generateUser([
			'role_ids' => [$role->id],
			'active' => 'abc',
		]));

		$response->assertStatus(422);
		$response->assertExactJson([
			'errors' => [
				'active' => [__('validation.boolean', ['attribute' => 'status'])],
			],
			'message' => 'The given data was invalid.'
		]);
	}

	public function test_a_new_user_can_be_created()
	{
		$user = User::factory()->create();
		$role = Role::where('name', 'user')->first();
		$user->attachRole('admin');

		$this->actingAs($user);
		$response = $this->json('POST', route('admin.users.store'), $newUserData = $this->generateUser([
			'role_ids' => [$role->id],
		]));

		$response->assertStatus(201);
		$response->assertExactJson([
			'message' => 'The user was created successfully.'
		]);

		$this->assertCount(2, User::all());
		$newUser = User::where('email', $newUserData['email'])->first();

		$this->assertJsonStringEqualsJsonString(json_encode([
			'first_name' => $newUserData['first_name'],
			'last_name' => $newUserData['last_name'],
			'email' => $newUserData['email'],
			'active' => (int)$newUserData['active']
		]), json_encode([
			'first_name' => $newUser->first_name,
			'last_name' => $newUser->last_name,
			'email' => $newUser->email,
			'active' => (int)$newUser->active
		]));

		$this->assertTrue(Hash::check($newUserData['password'], $newUser['password']));
		$this->assertTrue($newUser->hasRole($role->name));
	}
}
