<?php

namespace App\Search\Queries\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Webcityro\Larasearch\Search\Queries\Search;
use Webcityro\Larasearch\Search\Queries\EloquentSearch;

class UsersQuery extends Search
{

	use EloquentSearch;

	protected function query(): Builder
	{
		return User::query();
	}

	protected function filter(Builder $query, string $field, $value): Builder
	{
		if (in_array($field, ['id', 'active'])) {
			return $query->where($field, $value);
		}

		if ($field == 'role_ids') {
			return $query->whereHas('roles', fn (Builder $q) => $q->whereIn('id', $value));
		}

		return $query->where($field, 'LIKE', '%' . $value . '%');
	}
}
