<?php

use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\ImportArticleController;
use App\Http\Controllers\Api\V1\SearchArticleController;
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


Route::prefix('v1')->group(function () {
    Route::post('import', ImportArticleController::class)->name('api.v1.import_article');
    Route::post('search', SearchArticleController::class)->name('api.v1.search_article');
    Route::get('article/{article_id}', ArticleController::class)->name('api.v1.article');
});
