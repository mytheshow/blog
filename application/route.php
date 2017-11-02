<?php

use think\Route;

Route::any('/login', 'index/auth/login');
Route::get('/', 'index/index/index');
Route::any('/register', 'index/auth/register');
Route::post('/ajax_email', 'index/auth/ajax_email',['ajax' => true]);
Route::post('/ajax_username', 'index/auth/ajax_username',['ajax' => true]);
Route::get('/active_user/:activecode', 'index/auth/active_user');
Route::get('/confirm', 'index/auth/confirm');
Route::get('/user/:username', 'index/index/user');

//api路由
Route::post('/user', 'api/user/login');
// 获取验证码
Route::get('/code/:time/:token/:username/:is_exist','api/code/get_code');