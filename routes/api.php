<?php

use App\Http\Controllers\Admin\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthSocialiteController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('message',[MessageController::class,'index']);
});

Route::post('github',[AuthSocialiteController::class,'redirect']);
Route::get('github-callback',[AuthSocialiteController::class,'callback']);
