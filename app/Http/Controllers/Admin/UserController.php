<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Actions\Jetstream\DeleteUser;
use App\Http\Requests\Admin\UsersRequest;
use App\Http\Resources\Admin\UsersResource;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use Webcityro\Larasearch\Http\Resources\SearchCollection;
use App\Repositories\Contracts\Admin\UsersRepositoryContract;

class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('permission:users-read')->only(['index', 'fetch']);
		$this->middleware('permission:users-delete')->only('destroy');
	}

	public function index(): Response
	{
		$roles = Role::all()->mapWithKeys(function ($role) {
			return [$role->id => $role->display_name];
		});

		return Inertia::render('Admin/Users/Index')->with(compact('roles'));
	}

	public function fetch(UsersRequest $request, UsersRepositoryContract $repository): SearchCollection
	{
		return new SearchCollection(
			$repository->search($request),
			UsersResource::class
		);
	}

	public function create()
	{
	}

	public function store(StoreUserRequest $request): JsonResponse
	{
		$newUser = User::create($request->validated());
		$newUser->attachRoles($request->role_ids);
		return response()->json(['message' => 'The user was created successfully.'], 201);
	}

	public function show(User $user)
	{
		//
	}

	public function edit(User $user)
	{
		//
	}

	public function update(UpdateUserRequest $request, User $user): JsonResponse
	{
		$user->update($request->validated());
		$user->syncRoles($request->role_ids);
		return response()->json([
			'record' => new UsersResource($user->fresh()),
			'message' => 'The user was updated successfully.',
		]);
	}

	public function destroy(User $user, DeleteUser $deleteUser): JsonResponse
	{
		if ($user->id === auth()->id()) {
			return response()->json(['message' => 'You can\'t delete yourself dummy!'], 403);
		}

		$deleteUser->delete($user);

		return response()->json([
			'message' => 'The user was deleted successfully.',
		]);
	}
}
