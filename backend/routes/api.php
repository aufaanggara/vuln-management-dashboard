<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VulnerabilityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/vulnerabilities/export/pdf', [VulnerabilityController::class, 'exportPdf']);
    Route::apiResource('vulnerabilities', VulnerabilityController::class);
});