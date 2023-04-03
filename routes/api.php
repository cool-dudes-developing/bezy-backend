<?php

use App\Models\User;
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

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login', [App\Http\Controllers\Api\V1\Auth\LoginController::class, 'login'])->name('login');
    Route::post('register', [App\Http\Controllers\Api\V1\Auth\RegisterController::class, 'register'])->name('register');
    Route::post('forgot', [App\Http\Controllers\Api\V1\Auth\ForgotPasswordController::class, 'forgot'])->name('forgot');
    Route::post('reset', [App\Http\Controllers\Api\V1\Auth\ResetPasswordController::class, 'reset'])->name('reset');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::group(['prefix' => 'tokens', 'as' => 'tokens.'], function () {
            Route::get('/', [App\Http\Controllers\Api\V1\Auth\TokenController::class, 'index'])->name('index');
            Route::delete('/{token}', [App\Http\Controllers\Api\V1\Auth\TokenController::class, 'destroy'])->name('destroy');
        });
        Route::post('logout', [App\Http\Controllers\Api\V1\Auth\LoginController::class, 'logout'])->name('logout');
        Route::get('user', function (Request $request) {
            return $request->user();
        });
    });
});
