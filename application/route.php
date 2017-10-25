<?php

use think\Route;

Route::any('/login', 'index/auth/login');
Route::get('/', 'index/index/index');
