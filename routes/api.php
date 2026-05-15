<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/metrics', function () {

    $requests = cache()->get('requests_count', 0);
    $responseTime = cache()->get('response_time', 0);

    return response(
        "requests_count {$requests}\nresponse_time_ms {$responseTime}"
    )->header('Content-Type', 'text/plain');
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
    ]);
});

Route::get('/ready', function () {

    try {
        DB::connection()->getPdo();

        return response()->json([
            'status' => 'ready',
        ]);

    } catch (Exception $e) {

        return response()->json([
            'status' => 'not ready',
        ], 500);
    }
});

Route::middleware('throttle:api')
    ->prefix('v1')
    ->group(function () {

        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

    });

Route::prefix('v1')
    ->middleware(['auth:sanctum', 'throttle:api'])
    ->group(function () {

        Route::apiResource('tasks', TaskController::class);
        Route::get('logout', [AuthController::class, 'logout']);

    });
