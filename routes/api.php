<?php

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

    // Project module
    Route::get('projects/archived', [App\Http\Controllers\Api\V1\ProjectController::class, 'archived'])->name('projects.archived');
    Route::post('projects/{projectId}/restore', [App\Http\Controllers\Api\V1\ProjectController::class, 'restore'])->name('projects.archive');
    Route::delete('projects/{projectId}', [App\Http\Controllers\Api\V1\ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::apiResource('projects', App\Http\Controllers\Api\V1\ProjectController::class)->except('destroy');
    Route::apiResource('projects.methods', App\Http\Controllers\Api\V1\ProjectMethodController::class, [
        'parameters' => [
            'projects' => 'project',
            'methods' => 'block'
        ]
    ]);
    Route::apiResource('projects.tables', App\Http\Controllers\Api\V1\ProjectTableController::class, [
        'parameters' => [
            'projects' => 'project',
            'tables' => 'table'
        ]
    ]);

    Route::apiResource('projects.members', App\Http\Controllers\Api\V1\ProjectMemberController::class, [
        'parameters' => [
            'projects' => 'project',
            'members' => 'user'
        ]
    ]);
    Route::put('projects/{project}/accept', [App\Http\Controllers\Api\V1\ProjectMemberController::class, 'accept'])->name('projects.members.accept');
    Route::put('projects/{project}/reject', [App\Http\Controllers\Api\V1\ProjectMemberController::class, 'reject'])->name('projects.members.reject');

    Route::apiResource('projects.files', App\Http\Controllers\Api\V1\ProjectFileController::class, [
        'parameters' => [
            'projects' => 'project',
            'files' => 'file'
        ]
    ]);
    Route::apiResource('tables.columns', App\Http\Controllers\Api\V1\TableColumnController::class, [
        'parameters' => [
            'projects' => 'project',
            'tables' => 'table',
            'columns' => 'column'
        ]
    ]);
    Route::apiResource('tables.relations', App\Http\Controllers\Api\V1\TableRelationController::class, [
        'parameters' => [
            'tables' => 'table',
            'relations' => 'relation'
        ]
    ]);
    Route::get('tables/{table}/rows', [App\Http\Controllers\Api\V1\TableRowController::class, 'index'])->name('tables.rows.index');
    Route::put('tables/{table}/rows', [App\Http\Controllers\Api\V1\TableRowController::class, 'update'])->name('tables.rows.update');

    Route::post('projects/{project}/methods/{block}/execute', [App\Http\Controllers\Api\V1\ProjectMethodController::class, 'execute'])->name('projects.methods.execute');
    Route::post('projects/{project}/methods/{block}/exec', [App\Http\Controllers\Api\V1\ProjectMethodController::class, 'execute'])->name('projects.methods.execute');
    Route::post('projects/{project}/methods/{block}/debug', [App\Http\Controllers\Api\V1\ProjectMethodController::class, 'debug'])->name('projects.methods.execute');
    Route::get('blocks/templates', [App\Http\Controllers\Api\V1\BlockController::class, 'templates'])->name('blocks.templates');
    Route::post('methods/{method}/publish', [App\Http\Controllers\Api\V1\MethodController::class, 'publish'])->name('methods.publish');
    Route::apiResource('methods.blocks', App\Http\Controllers\Api\V1\MethodBlockController::class, ['parameters' => [
        'methods' => 'method',
        'blocks' => 'block'
    ]]);

    Route::get('assets/liked', [App\Http\Controllers\Api\V1\AssetController::class, 'liked'])->name('assets.liked');
    Route::get('assets/recent', [App\Http\Controllers\Api\V1\AssetController::class, 'recent'])->name('assets.liked');
    Route::post('assets/{asset}/like', [App\Http\Controllers\Api\V1\AssetController::class, 'like'])->name('assets.like');
    Route::resource('assets', App\Http\Controllers\Api\V1\AssetController::class);

    Route::resource('methods.ports', App\Http\Controllers\Api\V1\MethodPortController::class, ['parameters' => [
        'methods' => 'block',
        'ports' => 'port'
    ]]);

    Route::post('methods/{block}/connections', [App\Http\Controllers\Api\V1\ConnectionController::class, 'store'])->name('methods.connections.store');
    Route::delete('methods/{block}/connections/{connection}', [App\Http\Controllers\Api\V1\ConnectionController::class, 'destroy'])->name('methods.connections.destroy');
});
