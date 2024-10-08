<?php
/**
 * @OA\Info(
 *     title="News Aggregator API Documentation",
 *     version="1.0.0",
 *     description="This is the API documentation for the News Aggregator API."
 * )
 */


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\NewsFeedController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/password-reset-request', [AuthController::class, 'passwordResetRequest']);
Route::post('/password-reset', [AuthController::class, 'passwordReset']);



Route::get('/articles', [ArticleController::class, 'getArticles']);
Route::get('/articles/search', [ArticleController::class, 'searchArticles']);
Route::get('/articles/{id}', [ArticleController::class, 'getArticleDetails']);



Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/user/preferences', [NewsFeedController::class, 'getPreferences']);
    Route::get('/user/personalized-feed', [NewsFeedController::class, 'getPersonalizedFeed']);
    Route::post('/user/preferences', [NewsFeedController::class, 'setPreferences']);
});


Route::get('/test', function () {
    return 'API is working';
});
