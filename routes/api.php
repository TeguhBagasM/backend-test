<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\EmployeeController;
use Illuminate\Support\Facades\Route;

// Public routes (hanya bisa diakses ketika belum login)
Route::middleware('guest.api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes (hanya bisa diakses ketika sudah login)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/divisions', [DivisionController::class, 'index']);
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
});