<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['json.response', 'api']], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::group(['middleware' => ['auth:sanctum']], function () { 
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/create-task', [TaskController::class, 'create']);
        Route::get('/view-task/{task_id}', [TaskController::class, 'view']);
        Route::get('/list-task', [TaskController::class, 'listAll']);
        Route::get('/list-task/{by_tag}', [TaskController::class, 'listAll']);
        Route::get('/my-task', [TaskController::class, 'myTask']);
        Route::get('/my-task/{by_tag}', [TaskController::class, 'myTask']);
        Route::patch('/assign-task/{task_id}', [TaskController::class, 'assignTask']);
        Route::patch('/assign-task/{task_id}/{user_id}', [TaskController::class, 'assignTask']);
        Route::patch('/update-task-estimate/{task_id}', [TaskController::class, 'updateTaskTime']);
        Route::patch('/move-task-status/{task_id}', [TaskController::class, 'moveTask']);
        Route::delete('/delete-task/{task_id}', [TaskController::class, 'deleteTask']);

        Route::get('/logout', [AuthController::class, 'logout']);
    });
});