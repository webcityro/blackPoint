<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{

	public function toArray($request): array
	{
		return [
			'id' => $this->id,
			'first_name' => $this->first_name,
			'last_name' => $this->last_name,
			'email' => $this->email,
			'roles' => $this->roles->map(fn ($role) => $role->only(['id', 'name', 'display_name'])),
			'active' => (int)$this->active,
			'avatar' => $this->profile_photo_url,
			'destroyURL' => route('admin.users.destroy', $this->id),
		];
	}
}
