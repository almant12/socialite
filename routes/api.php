<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthSocialiteController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('github',[AuthSocialiteController::class,'redirect']);
Route::get('github-callback',[AuthSocialiteController::class,'callback']);


Route::get('auth',[AuthSocialiteController::class,'authUser'])->middleware('auth:sanctum');

