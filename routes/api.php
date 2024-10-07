<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/password-reset-request', [AuthController::class, 'passwordResetRequest']);
Route::post('/password-reset', [AuthController::class, 'passwordReset']);

Route::get('/articles', [ArticleController::class, 'getArticles']);
Route::get('/articles/search', [ArticleController::class, 'searchArticles']);
Route::get('/articles/{id}', [ArticleController::class, 'getArticleDetails']);

Route::get('/test', function () {
    return 'API is working';
});
