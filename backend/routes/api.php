<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Authenticated routes
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

});

//Route::prefix('employees')->middleware('auth:sanctum')->group(function () {
//    Route::get('/', [EmployeeController::class, 'index']);
//    Route::post('/', [EmployeeController::class, 'store']); 
//    Route::get('{id}', [EmployeeController::class, 'show']); 
//    Route::put('{id}', [EmployeeController::class, 'update']); 
//    Route::delete('{id}', [EmployeeController::class, 'destroy']); 
//});

Route::prefix('employees')->group(function () {
    Route::get('/', [EmployeeController::class, 'index']);
    Route::post('/', [EmployeeController::class, 'store']); 
    Route::get('{id}', [EmployeeController::class, 'show']); 
    Route::put('{id}', [EmployeeController::class, 'update']); 
    Route::delete('{id}', [EmployeeController::class, 'destroy']); 
});