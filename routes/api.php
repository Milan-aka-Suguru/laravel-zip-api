<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountyController;
use App\Http\Controllers\TownController;
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/counties', [CountyController::class, 'index']);
    Route::post('/counties', [CountyController::class, 'store']);
    Route::put('/counties/{id}', [CountyController::class, 'update']);
    Route::patch('/counties/{id}', [CountyController::class, 'update']);
    Route::delete('/counties/{id}', [CountyController::class, 'destroy']);
    Route::get('/counties/{id}', [CountyController::class, 'show']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/towns', [TownController::class, 'index']);
    Route::post('/towns', [TownController::class, 'store']);
    Route::put('/towns/{id}', [TownController::class, 'update']);
    Route::patch('/towns/{id}', [TownController::class, 'update']);
    Route::delete('/towns/{id}', [TownController::class, 'destroy']);
    Route::get('/towns/{id}', [TownController::class, 'show']);
});
Route::post('/users/login',[UserController::class, 'login']);
Route::get('/users',[UserController::class, 'index'])->middleware('auth:sanctum');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
