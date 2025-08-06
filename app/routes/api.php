<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TokenController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->post('/estimate', [EstimateController::class, 'getEstimate']);

// 로그인 API는 인증 미들웨어 없이 접근 가능해야 합니다.
Route::post('/login', [LoginController::class, 'login']);
Route::post('/token/generate', [TokenController::class, 'generateToken']);
Route::post('/token/get', [TokenController::class, 'getToken']);

// 토큰이 필요한 API 라우트
Route::middleware('auth:sanctum')->group(function () {
    // 토큰이 있어야만 접근 가능한 라우트들
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/estimate', [EstimateController::class, 'getEstimate']);
});