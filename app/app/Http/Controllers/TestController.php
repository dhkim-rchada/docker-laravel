<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

class TestController extends Controller
{
    /**
     * PHP 버전과 Laravel 버전을 출력합니다.
     */
    public function index(Application $app)
    {
        // PHP 버전 정보 가져오기
        $phpVersion = phpversion();

        // Laravel 버전 정보 가져오기
        $laravelVersion = $app::VERSION;

        // 화면에 출력
        return "<h1>PHP 버전: {$phpVersion}</h1>"
             . "<h1>Laravel 버전: {$laravelVersion}</h1>"
             ."test";
    }
}
