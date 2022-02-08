<?php

use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => 'auth',
], static function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('verify', [RegisterController::class, 'verify']);
    Route::post('password/reset', [PasswordResetController::class, 'reset']);
    Route::post('login', [LoginController::class, 'login']);
    Route::delete('logout', [LoginController::class, 'logout']);
});
/** Admin */
Route::group([
    'middleware' => ['auth:sanctum', 'can:admin-panel'],
    'prefix'     => 'admin',
], static function () {
    Route::apiResources([
            'users' => UserController::class,
        ]
    );
});
/** Auth User */
Route::middleware('auth:sanctum')
    ->get('/user', static function (Request $request) {
        return new UserResource($request->user());
    });

