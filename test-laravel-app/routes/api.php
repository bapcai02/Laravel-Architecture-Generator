<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Laravel Architex Test Routes
Route::prefix('architex-test')->group(function () {
    // Repository Pattern & Service Layer Tests
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    
    // Search functionality
    Route::get('/users/search', [UserController::class, 'search']);
    
    // CQRS Pattern Test
    Route::post('/cqrs-test', [UserController::class, 'testCqrs']);
});

// Health check route
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'message' => 'Laravel Architex Test Application is running',
        'timestamp' => now(),
        'architecture_patterns' => [
            'Repository Pattern' => 'Available',
            'Service Layer' => 'Available',
            'DDD' => 'Available',
            'CQRS' => 'Available',
            'Event Bus' => 'Available'
        ]
    ]);
});
