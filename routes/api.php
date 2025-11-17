<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountyController;
use App\Http\Controllers\TownController;
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/counties', [CountyController::class, 'store']);
    Route::put('/counties/{id}', [CountyController::class, 'update']);
    // Route::patch('/counties/{id}', [CountyController::class, 'update']);
    Route::delete('/counties/{id}', [CountyController::class, 'destroy']);
    Route::get('/counties/{id}', [CountyController::class, 'show']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/towns', [TownController::class, 'store']);
    Route::put('/towns/{id}', [TownController::class, 'update']);
    // Route::patch('/towns/{id}', [TownController::class, 'update']);
    Route::delete('/towns/{id}', [TownController::class, 'destroy']);
    Route::get('/towns/{id}', [TownController::class, 'show']);
});
Route::get('/towns', [TownController::class, 'index']);
Route::get('/counties', [CountyController::class, 'index']);
Route::post('/users/login',[UserController::class, 'login']);
Route::get('/users',[UserController::class, 'index'])->middleware('auth:sanctum');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/users/logout', function (Request $request) {
    $request->user()->tokens()->delete(); // revoke all tokens
    return response()->json(['message' => 'Logged out']);
})->middleware('auth:sanctum');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
->name('logout');
