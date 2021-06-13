<?php

namespace App\Repositories\Contracts\Admin;

use Webcityro\Larasearch\Http\Requests\SearchFormRequest;
use Webcityro\Larasearch\Search\Queries\Search;

interface UsersRepositoryContract {

	public function search(SearchFormRequest $request): Search;
}
