<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Eloquent\Admin\UsersRepository;
use App\Repositories\Contracts\Admin\UsersRepositoryContract;

class AppServiceProvider extends ServiceProvider
{

    public function register():void
    {
        //
    }

    public function boot():void
    {
        Schema::defaultStringLength(191);
        JsonResource::withoutWrapping();

        $this->app->bind(UsersRepositoryContract::class, UsersRepository::class);
    }
}
