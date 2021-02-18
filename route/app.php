<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

//


Route::group('api', function () {
    Route::rule('action', 'action');

})->prefix('api/')
    ->middleware(\app\middleware\AdminAuth::class);


Route::group('user', function () {
    Route::rule('log', 'log');
    Route::rule('post', 'post');
    Route::rule('test', 'test');
    Route::rule('/', 'index');

})->prefix('user/')
    ->middleware(\app\middleware\UserAuth::class);