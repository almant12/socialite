<?php

use App\Http\Controllers\Admin\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthSocialiteController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return response()->json(['status'=>'success'],200);
});

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('user-message',[MessageController::class,'index']);
    Route::get('message/{id}',[MessageController::class,'getMessage']);
});

Route::get('github',[AuthSocialiteController::class,'githubRedirect']);
Route::get('callback',[AuthSocialiteController::class,'githubCallback']);
