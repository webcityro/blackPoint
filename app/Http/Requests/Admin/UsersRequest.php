<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Webcityro\Larasearch\Http\Requests\SearchFormRequest;
use Webcityro\Larasearch\Http\Requests\SearchRequest;
use Webcityro\Larasearch\Search\Payloads\MultiFieldsPayload;
use Webcityro\Larasearch\Search\Payloads\Payload;

class UsersRequest extends FormRequest implements SearchFormRequest
{

	use SearchRequest;

	public function authorize(): bool
	{
		return true;
	}

	public function searchFields(): array
	{
		return ['id', 'first_name', 'last_name', 'email', 'role_ids', 'active'];
	}

	protected function orderByFields(): array
	{
		return $this->searchFields();
	}

	protected function defaultOrderByField(): string
	{
		return 'id';
	}

	protected function payload(): Payload
	{
		return new MultiFieldsPayload($this->search ?? []);
	}
}
