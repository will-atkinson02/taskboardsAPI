<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

    Route::get('/user/{username}', [\App\Http\Controllers\AuthController::class, 'getUser']);

    Route::get('/taskboard/{id}', [\App\Http\Controllers\TaskboardController::class, 'getTaskBoardData']);
    Route::post('/taskboard', [\App\Http\Controllers\TaskboardController::class, 'createTaskboard']);
    Route::put('/taskboard/{id}', [\App\Http\Controllers\TaskboardController::class, 'changeTaskboardName']);

    Route::post('/stage', [\App\Http\Controllers\StageController::class, 'createStage']);
    Route::put('/stage/{id}', [\App\Http\Controllers\StageController::class, 'updateStage']);
    Route::delete('/stage/{id}', [\App\Http\Controllers\StageController::class, 'deleteStage']);

    Route::get('/task', [\App\Http\Controllers\TaskController::class, 'getTaskIdByName']);
    Route::post('/task', [\App\Http\Controllers\TaskController::class, 'createTask']);
    Route::put('/task/{id}', [\App\Http\Controllers\TaskController::class, 'updateTask']);
    Route::delete('/task/{id}', [\App\Http\Controllers\TaskController::class, 'deleteTask']);

});

