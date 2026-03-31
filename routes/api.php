<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::prefix('V1')->group(function () {

    // ─── Public Routes (بدون توكن) ───────────────────────────────────────────────
    Route::post('/register',        [AuthController::class, 'register']);
    Route::middleware('auth:sanctum')->post('/login',           [AuthController::class, 'login']);
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);

    // ─── Protected Routes (Bearer Token مطلوب) ───────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        // باقي الـ modules هتتضاف هنا
        // Route::get('/profile',            [ProfileController::class, 'show']);
        // Route::post('/attendance/mark',   [AttendanceController::class, 'mark']);
        // Route::post('/evaluations',       [EvaluationController::class, 'store']);
        // Route::post('/payments/pay',      [PaymentController::class, 'pay']);
        // Route::get('/posts',              [PostController::class, 'index']);
        // Route::get('/schedules',          [ScheduleController::class, 'index']);
    });
});
