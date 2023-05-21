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

// Auth module
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

// Project module
Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('projects', App\Http\Controllers\Api\V1\ProjectController::class);
    Route::apiResource('projects.methods', App\Http\Controllers\Api\V1\ProjectBlockController::class, [
        'parameters' => [
            'projects' => 'project',
            'methods' => 'block'
        ]
    ]);
    Route::post('projects/{project}/methods/{block}/execute', [App\Http\Controllers\Api\V1\ProjectBlockController::class, 'execute'])->name('projects.methods.execute');
    Route::post('projects/{project}/methods/{block}/debug', [App\Http\Controllers\Api\V1\ProjectBlockController::class, 'debug'])->name('projects.methods.execute');
    Route::get('blocks/templates', [App\Http\Controllers\Api\V1\BlockController::class, 'templates'])->name('blocks.templates');
    Route::apiResource('methods.blocks', App\Http\Controllers\Api\V1\MethodBlockController::class, ['parameters' => [
        'methods' => 'method',
        'blocks' => 'block'
    ]]);

    Route::post('methods/{block}/connections', [App\Http\Controllers\Api\V1\ConnectionController::class, 'store'])->name('methods.connections.store');
    Route::delete('methods/{block}/connections/{connection}', [App\Http\Controllers\Api\V1\ConnectionController::class, 'destroy'])->name('methods.connections.destroy');
});
