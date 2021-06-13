<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{

	public function authorize(): bool
	{
		return Auth::user()->isAbleTo('users-update');
	}

	public function rules(): array
	{
		return [
			'first_name' => ['required', 'string', 'max:50'],
			'last_name' => ['required', 'string', 'max:50'],
			'email' => ['required', 'string', 'max:50', 'email', Rule::unique('users')->ignore($this->user->id)],
			'role_ids' => ['required', 'array', Rule::exists('roles', 'id')],
			'active' => ['required', 'boolean']
		];
	}

	public function validated(): array
	{
		$data = parent::validated();
		unset($data['role_ids']);
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
