<?php

namespace Tests\Feature\Admin\Users;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchUsersTest extends TestCase
{

	use RefreshDatabase;

	private $userRole;
	private $adminRole;
	private $admin;
	private $user;

	public function setUp(): void
	{
		parent::setUp();

		config(['larasearch.per_page' => [1, 2, 3, 10]]);
		config(['larasearch.default_per_page' => [1]]);

		$this->adminRole = Role::where('name', 'admin')->first();
		$this->userRole = Role::where('name', 'user')->first();
		$this->user = User::factory()->create();
		$this->user->attachRole('user');
		$this->admin = User::factory()->create();
		$this->admin->attachRole('admin');
		$this->actingAs($this->admin);
	}

	public function test_can_not_search_user_without_permission()
	{
		$this->actingAs($this->user);
		$response = $this->json('GET', route('admin.users.fetch'), [
			'search' => json_encode([
				'id' => '',
				'first_name' => '',
				'last_name' => '',
				'email' => '',
				'role_ids' => [],
				'active' => '',
			]),
			'per_page' => 10,
			'page' => 1,
			'order_by' => 'id:desc'
		]);

		$response->assertStatus(403);
	}

	public function test_users_search_works_with_empty_array_as_tha_search_param()
	{
		$users = User::factory()->count(10)->create()->map(function (User $user) {
			$user->attachRole($this->userRole->id);
			return array_merge(
				$user->only('id', 'first_name', 'last_name', 'email', 'active'),
				[
					'roles' => [$this->userRole->only(['id', 'name', 'display_name'])],
					'active' => (int)$user->active,
					'avatar' => $user->profile_photo_url,
					'destroyURL' => route('admin.users.destroy', $user->id),
				]
			);
		});

		$response = $this->json('GET', route('admin.users.fetch', [
			'search' => json_encode([
				'id' => '',
				'first_name' => '',
				'last_name' => '',
				'email' => '',
				'role_ids' => [],
				'active' => '',
			]),
			'per_page' => 10,
			'page' => 1,
			'order_by' => 'id:desc'
		]));

		$records = $users->sortByDesc('id')->skip(0)->take(10)->values()->toArray();

		$response->assertStatus(200);
		$response->assertExactJson([
			'params' => [
				'search' => [
					'id' => '',
					'first_name' => '',
					'last_name' => '',
					'email' => '',
					'role_ids' => [],
					'active' => '',
				],
				'per_page' => 10,
				'page' => 1,
				'order_by' => 'id:desc',
			],
			'meta' => [
				'total' => 12,
				'prev_page' => null,
				'next_page' => 2,
				'last_page' => 2,
			],
			'records' => $records,
		]);
	}

	public function test_users_search_works_with_param()
	{
		$users = collect([
			User::factory([
				'id' => 3,
				'first_name' => 'Vasile',
				'last_name' => 'Ion',
				'email' => 'vasile_ion@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 4,
				'first_name' => 'Mihai',
				'last_name' => 'popescu',
				'email' => 'popescu_mihai@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 5,
				'first_name' => 'Radu',
				'last_name' => 'popescu',
				'email' => 'popescu_radu87@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 6,
				'first_name' => 'Radu',
				'last_name' => 'constantin',
				'email' => 'constantin_radu87@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 7,
				'first_name' => 'Simona',
				'last_name' => 'Ion',
				'email' => 'simona_ion@gmail.com',
				'active' => true,
			])->create(),
		])->map(function ($user) {
			$user->attachRole($this->userRole->id);
			return array_merge(
				$user->only('id', 'first_name', 'last_name', 'email', 'active'),
				[
					'roles' => [$this->userRole->only(['id', 'name', 'display_name'])],
					'active' => (int)$user->active,
					'avatar' => $user->profile_photo_url,
					'destroyURL' => route('admin.users.destroy', $user->id),
				]
			);
		});

		$response = $this->json('GET', route('admin.users.fetch', [
			'search' => json_encode([
				'id' => '',
				'first_name' => 'radu',
				'last_name' => '',
				'email' => '',
				'role_ids' => [],
				'active' => '',
			]),
			'per_page' => 2,
			'page' => 1,
			'order_by' => 'id:desc'
		]));

		$records = $users->whereIn('id', [5, 6])->sortByDesc('id')->values()->toArray();

		$response->assertStatus(200);
		$response->assertExactJson([
			'params' => [
				'search' => [
					'id' => '',
					'first_name' => 'radu',
					'last_name' => '',
					'email' => '',
					'role_ids' => [],
					'active' => '',
				],
				'per_page' => 2,
				'page' => 1,
				'order_by' => 'id:desc',
			],
			'meta' => [
				'total' => 2,
				'prev_page' => null,
				'next_page' => null,
				'last_page' => 1,
			],
			'records' => $records,
		]);
	}

	public function test_users_search_works_with_multiple_param()
	{
		$users = collect([
			User::factory([
				'id' => 3,
				'first_name' => 'Vasile',
				'last_name' => 'Ion',
				'email' => 'vasile_ion@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 4,
				'first_name' => 'Mihai',
				'last_name' => 'popescu',
				'email' => 'popescu_mihai@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 5,
				'first_name' => 'Radu',
				'last_name' => 'popescu',
				'email' => 'popescu_radu87@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 6,
				'first_name' => 'Radu',
				'last_name' => 'constantin',
				'email' => 'constantin_radu87@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 7,
				'first_name' => 'Simona',
				'last_name' => 'Ion',
				'email' => 'simona_ion@gmail.com',
				'active' => true,
			])->create(),
		])->map(function ($user) {
			$user->attachRole($this->userRole->id);
			return array_merge(
				$user->only('id', 'first_name', 'last_name', 'email', 'active'),
				[
					'roles' => [$this->userRole->only(['id', 'name', 'display_name'])],
					'active' => (int)$user->active,
					'avatar' => $user->profile_photo_url,
					'destroyURL' => route('admin.users.destroy', $user->id),
				]
			);
		});

		$response = $this->json('GET', route('admin.users.fetch', [
			'search' => json_encode([
				'id' => '',
				'first_name' => '',
				'last_name' => 'ion',
				'email' => 'simona',
				'active' => '',
			]),
			'per_page' => 2,
			'page' => 1,
			'order_by' => 'id:desc'
		]));

		$records = $users->whereIn('id', [7])->sortByDesc('id')->values()->toArray();

		$response->assertStatus(200);
		$response->assertExactJson([
			'params' => [
				'search' => [
					'id' => '',
					'first_name' => '',
					'last_name' => 'ion',
					'email' => 'simona',
					'active' => '',
				],
				'per_page' => 2,
				'page' => 1,
				'order_by' => 'id:desc',
			],
			'meta' => [
				'total' => 1,
				'prev_page' => null,
				'next_page' => null,
				'last_page' => 1,
			],
			'records' => $records,
		]);
	}

	public function test_users_search_works_when_filtering_by_id()
	{
		$users = collect([
			User::factory([
				'id' => 3,
				'first_name' => 'Vasile',
				'last_name' => 'Ion',
				'email' => 'vasile_ion@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 30,
				'first_name' => 'Mihai',
				'last_name' => 'popescu',
				'email' => 'popescu_mihai@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 13,
				'first_name' => 'Radu',
				'last_name' => 'popescu',
				'email' => 'popescu_radu87@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 31,
				'first_name' => 'Radu',
				'last_name' => 'constantin',
				'email' => 'constantin_radu87@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 43,
				'first_name' => 'Simona',
				'last_name' => 'Ion',
				'email' => 'simona_ion@gmail.com',
				'active' => true,
			])->create(),
		])->map(function ($user) {
			$user->attachRole($this->userRole->id);
			return array_merge(
				$user->only('id', 'first_name', 'last_name', 'email', 'active'),
				[
					'roles' => [$this->userRole->only(['id', 'name', 'display_name'])],
					'active' => (int)$user->active,
					'avatar' => $user->profile_photo_url,
					'destroyURL' => route('admin.users.destroy', $user->id),
				]
			);
		});

		$response = $this->json('GET', route('admin.users.fetch', [
			'search' => json_encode([
				'id' => '3',
				'first_name' => '',
				'last_name' => '',
				'email' => '',
				'role_ids' => [],
				'active' => '',
			]),
			'per_page' => 2,
			'page' => 1,
			'order_by' => 'id:desc'
		]));

		$records = $users->whereIn('id', [3])->sortByDesc('id')->values()->toArray();

		$response->assertStatus(200);
		$response->assertExactJson([
			'params' => [
				'search' => [
					'id' => '3',
					'first_name' => '',
					'last_name' => '',
					'email' => '',
					'role_ids' => [],
					'active' => '',
				],
				'per_page' => 2,
				'page' => 1,
				'order_by' => 'id:desc',
			],
			'meta' => [
				'total' => 1,
				'prev_page' => null,
				'next_page' => null,
				'last_page' => 1,
			],
			'records' => $records,
		]);
	}

	public function test_users_search_works_when_filtering_by_active()
	{
		$users = collect([
			User::factory([
				'id' => 3,
				'first_name' => 'Vasile',
				'last_name' => 'Ion',
				'email' => 'vasile_ion@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 4,
				'first_name' => 'Mihai',
				'last_name' => 'popescu',
				'email' => 'popescu_mihai@gmail.com',
				'active' => false,
			])->create(),
			User::factory([
				'id' => 5,
				'first_name' => 'Radu',
				'last_name' => 'popescu',
				'email' => 'popescu_radu87@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 6,
				'first_name' => 'Radu',
				'last_name' => 'constantin',
				'email' => 'constantin_radu87@gmail.com',
				'active' => false,
			])->create(),
			User::factory([
				'id' => 7,
				'first_name' => 'Simona',
				'last_name' => 'Ion',
				'email' => 'simona_ion@gmail.com',
				'active' => true,
			])->create(),
		])->map(function ($user) {
			$user->attachRole($this->userRole->id);
			return array_merge(
				$user->only('id', 'first_name', 'last_name', 'email', 'active'),
				[
					'roles' => [$this->userRole->only(['id', 'name', 'display_name'])],
					'active' => (int)$user->active,
					'avatar' => $user->profile_photo_url,
					'destroyURL' => route('admin.users.destroy', $user->id),
				]
			);
		});

		$response = $this->json('GET', route('admin.users.fetch', [
			'search' => json_encode([
				'id' => '',
				'first_name' => '',
				'last_name' => '',
				'email' => '',
				'role_ids' => [],
				'active' => true,
			]),
			'per_page' => 3,
			'page' => 1,
			'order_by' => 'id:desc'
		]));

		$records = $users->whereIn('id', [3, 5, 7])->sortByDesc('id')->values()->toArray();

		$response->assertStatus(200);
		$response->assertExactJson([
			'params' => [
				'search' => [
					'id' => '',
					'first_name' => '',
					'last_name' => '',
					'email' => '',
					'role_ids' => [],
					'active' => true,
				],
				'per_page' => 3,
				'page' => 1,
				'order_by' => 'id:desc',
			],
			'meta' => [
				'total' => 5,
				'prev_page' => null,
				'next_page' => 2,
				'last_page' => 2,
			],
			'records' => $records,
		]);
	}

	public function test_users_search_works_when_filtering_by_role_ids()
	{
		$this->withoutExceptionHandling();
		$users = collect([
			User::factory([
				'id' => 3,
				'first_name' => 'Vasile',
				'last_name' => 'Ion',
				'email' => 'vasile_ion@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 4,
				'first_name' => 'Mihai',
				'last_name' => 'popescu',
				'email' => 'popescu_mihai@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 5,
				'first_name' => 'Radu',
				'last_name' => 'popescu',
				'email' => 'popescu_radu87@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 6,
				'first_name' => 'Radu',
				'last_name' => 'constantin',
				'email' => 'constantin_radu87@gmail.com',
				'active' => true,
			])->create(),
			User::factory([
				'id' => 7,
				'first_name' => 'Simona',
				'last_name' => 'Ion',
				'email' => 'simona_ion@gmail.com',
				'active' => true,
			])->create(),
		])->map(function ($user) {
			$role = $user->id % 2 == 0 ? $this->userRole : $this->adminRole;
			$user->attachRole($role->id);
			return array_merge(
				$user->only('id', 'first_name', 'last_name', 'email', 'active'),
				[
					'roles' => [$role->only(['id', 'name', 'display_name'])],
					'active' => (int)$user->active,
					'avatar' => $user->profile_photo_url,
					'destroyURL' => route('admin.users.destroy', $user->id),
				]
			);
		});

		$response = $this->json('GET', route('admin.users.fetch', [
			'search' => json_encode([
				'id' => '',
				'first_name' => '',
				'last_name' => '',
				'email' => '',
				'role_ids' => [$this->adminRole->id],
				'active' => '',
			]),
			'per_page' => 3,
			'page' => 1,
			'order_by' => 'id:desc'
		]));

		$records = $users->whereIn('id', [3, 5, 7])->sortByDesc('id')->values()->toArray();

		$response->assertStatus(200);
		$response->assertExactJson([
			'params' => [
				'search' => [
					'id' => '',
					'first_name' => '',
					'last_name' => '',
					'email' => '',
					'role_ids' => [$this->adminRole->id],
					'active' => '',
				],
				'per_page' => 3,
				'page' => 1,
				'order_by' => 'id:desc',
			],
			'meta' => [
				'total' => 4,
				'prev_page' => null,
				'next_page' => 2,
				'last_page' => 2,
			],
			'records' => $records,
		]);
	}
}
