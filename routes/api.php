<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/user', [\App\Http\Controllers\AppUserController::class, 'createUser']);

Route::get('/taskboard/{id}', [\App\Http\Controllers\TaskboardController::class, 'getTaskBoardData']);
Route::post('/taskboard', [\App\Http\Controllers\TaskboardController::class, 'createTaskboard']);

Route::post('/stage', [\App\Http\Controllers\StageController::class, 'createStage']);

Route::get('/task', [\App\Http\Controllers\TaskController::class, 'getTaskIdByName']);
Route::post('/task', [\App\Http\Controllers\TaskController::class, 'createTask']);
Route::put('/task/{id}', [\App\Http\Controllers\TaskController::class, 'moveTask']);