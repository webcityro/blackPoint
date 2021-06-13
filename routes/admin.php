<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::get('users/fetch', [UserController::class, 'fetch'])->name('users.fetch');
Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
