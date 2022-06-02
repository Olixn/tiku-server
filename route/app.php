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

Route::get('hello/:name', 'index/hello');

Route::post('active','Api/ActiveScript');
Route::get('notice','Api/GetNotice');
Route::get('authStatus','Api/GetAuthStatus');

Route::group('cx',function () {
    Route::get('encode','Api/GetEnc');
    Route::post('getAnswer','Api/GetCxAnswer');
    Route::post('upload','Api/UpDateCxAnswer');
})->middleware('check');

Route::group('admin',function () {
    Route::get('addCode','Admin/AddCode');
});