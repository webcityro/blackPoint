<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        User::create([
            'first_name' => 'Andrei',
            'last_name' => 'valcu',
            'email' => 'andreivalcu@gmail.com',
            'password' => Hash::make('alexandra'),
            'active' => true,
        ])->attachRole('admin');
    }
}
