<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    public function run():void
    {
        // \App\Models\User::factory(10)->create();
        $this->call(LaratrustSeeder::class);

        if (!App::runningUnitTests()) {
            $this->call(UsersTableSeeder::class);
        }
    }
}
