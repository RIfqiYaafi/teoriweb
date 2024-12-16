<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Orders;
use App\Http\Controllers\Api\OrdersController;

// Define routes for user authentication
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(Authenticate::using('sanctum'));

Route::delete('users/{id}', [PostController::class, 'destroy']);

Route::resource('orders', OrdersController::class);

// Define API routes for posts
Route::apiResource('/posts', App\Http\Controllers\Api\PostController::class);
