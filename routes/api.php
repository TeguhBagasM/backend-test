<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::post('/login', [AuthController::class, 'login']);
Route::middleware('guest:admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});


// Protected routes - hanya bisa diakses jika sudah login
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/divisions', [DivisionController::class, 'index']);
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
});