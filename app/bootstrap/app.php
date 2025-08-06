<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // MethodNotAllowedHttpException 예외 처리 추가
        $exceptions->render(function (MethodNotAllowedHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => '유효하지 않은 요청입니다. POST 메서드를 사용해 주세요.',
                'errors' => [
                    'method' => ['허용되지 않은 HTTP 메서드입니다.']
                ]
            ], 405, [], JSON_UNESCAPED_UNICODE);
        });
    })->create();
