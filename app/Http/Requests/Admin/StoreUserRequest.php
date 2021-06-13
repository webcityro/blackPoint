<?php

namespace App\Http\Requests\Admin;

use Laravel\Fortify\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{

	public function authorize(): bool
	{
		return Auth::user()->isAbleTo('users-create');
	}

	public function rules(): array
	{
		return [
			'first_name' => ['required', 'string', 'max:50'],
			'last_name' => ['required', 'string', 'max:50'],
			'email' => ['required', 'string', 'max:50', 'email', 'unique:users'],
			'password' => ['required', 'string', new Password],
			'role_ids' => ['required', 'array', 'exists:roles,id'],
			'active' => ['required', 'boolean'],
		];
	}

	public function validated()
	{
		$data = parent::validated();
		unset($data['role_ids']);
		$data['password'] = Hash::make($data['password']);
		return $data;
	}

	public function attributes(): array
	{
		return [
			'active' => 'status',
			'role_ids' => 'roles',
		];
	}
}
