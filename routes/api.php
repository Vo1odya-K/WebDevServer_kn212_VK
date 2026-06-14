<?php

use App\Http\Controllers\Api\Blog\Admin\CategoryController;
use App\Http\Controllers\Api\Blog\Admin\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiggingDeeperController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('blog')->group(function () {
    Route::apiResource('posts', PostController::class)->names('blog.posts');
});

$groupData = [
    'prefix' => 'admin/blog',
];
Route::group($groupData, function () {
    // Повноцінний CRUD для категорій (додали show та destroy)
    Route::apiResource('categories', CategoryController::class)
        ->names('blog.admin.categories');

    // Повноцінний CRUD для статей (методи index, store, update, destroy працюють тут)
    Route::apiResource('posts', PostController::class)
        ->except(['show'])
        ->names('blog.admin.posts');
});

Route::prefix('digging_deeper')->group(function () {
    Route::get('process-video', [DiggingDeeperController::class, 'processVideo'])
        ->name('digging_deeper.processVideo');

    Route::get('prepare-catalog', [DiggingDeeperController::class, 'prepareCatalog'])
        ->name('digging_deeper.prepareCatalog');
});
