<?php

namespace App\Repositories\Eloquent\Admin;

use App\Search\Queries\Admin\UsersQuery;
use Webcityro\Larasearch\Search\Queries\Search;
use Webcityro\Larasearch\Http\Requests\SearchFormRequest;
use App\Repositories\Contracts\Admin\UsersRepositoryContract;

class UsersRepository implements UsersRepositoryContract {

	public function search(SearchFormRequest $request): Search {
		return (new UsersQuery($request->requestParams(), $request->requestOrder(), $request->searchFields()));
	}
}
