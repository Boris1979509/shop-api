<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->get('/user', static function (Request $request) {
        return $request->user();
    });
Route::group([
    'prefix' => 'auth',
], static function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('verify', [RegisterController::class, 'verify']);
    Route::post('login', [LoginController::class, 'login']);
    Route::delete('logout', [LoginController::class, 'logout']);
});
