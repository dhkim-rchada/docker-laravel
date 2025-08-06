<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 'auth' 별칭을 사용자 정의 Authenticate 미들웨어에 연결
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
        ]);

        // CORS 미들웨어 등록 (모든 요청에 대해 CORS 처리)
        $middleware->use([\Illuminate\Http\Middleware\HandleCors::class]);

        // API 미들웨어 그룹에 Sanctum 및 Throttle 미들웨어 추가
        // 'api' 그룹은 routes/api.php의 라우트에 자동으로 적용됩니다.
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Http\Middleware\HandleCors::class, // API 그룹에 직접 추가
        ]);
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

         // 💡 인증 오류(AuthenticationException) 예외 처리 추가
        $exceptions->render(function (AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => '인증되지 않은 사용자입니다. 유효한 토큰이 필요합니다.'
            ], 401, [], JSON_UNESCAPED_UNICODE);
        });
    })->create();
