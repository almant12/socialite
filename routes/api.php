<?php

use App\Http\Controllers\Auth\AuthSocialiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('github',[AuthSocialiteController::class,'redirect']);
Route::get('github-callback',[AuthSocialiteController::class,'callback']);