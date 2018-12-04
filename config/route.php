<?php

use \think\Route;

/*
 * 参数过滤
 */
Route::pattern('nsid', '^[0-9a-zA-Z]{5,10}$');
Route::pattern('cid', '^[0-9]+$');
Route::pattern('page', '^[0-9]+$');

/*
 * 测试
 */
Route::controller('test', 'TestController');

/**
 * 用户登陆
 */
Route::post('user/login$', 'UserController/login');
/**
 * 更新用户信息
 */
Route::post('user/update', 'UserController/updateInfo');

/**
 * 获取用户信息
 */
Route::get('user/info', 'UserController/user');
