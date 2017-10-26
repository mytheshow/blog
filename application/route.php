<?php

use think\Route;

Route::any('/login', 'index/auth/login');
Route::get('/', 'index/index/index');
Route::any('/register', 'index/auth/register');
Route::post('/ajax_email', 'index/auth/ajax_email',['ajax' => true]);
Route::post('/ajax_username', 'index/auth/ajax_username',['ajax' => true]);
