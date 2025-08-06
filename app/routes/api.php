<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->post('/estimate', [EstimateController::class, 'getEstimate']);

// 로그인 API는 인증 미들웨어 없이 접근 가능해야 합니다.
Route::post('/login', [LoginController::class, 'login']);

Route::post('/estimate', [EstimateController::class, 'getEstimate']);

// 토큰이 필요한 API 라우트
Route::middleware('auth:sanctum')->group(function () {
    // Route::post('/estimate', [EstimateController::class, 'getEstimate']);
    // ... 다른 인증이 필요한 라우트들 ...
});