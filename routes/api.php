<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// User routes
Route::group(['as' => 'users.', 'controller' => UserController::class], function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
    Route::post('logout', 'logout')->name('logout')->middleware('auth:sanctum');
});

// Category routes
Route::apiResource('categories', CategoryController::class)->middleware('auth:sanctum');
Route::apiResource('tags', TagController::class)->only('index')->middleware(['auth:sanctum', 'superadmin']);
Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');
