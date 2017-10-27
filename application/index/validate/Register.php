<?php
namespace app\index\validate;

use think\Validate;

class Register extends Validate
{
    protected $rule = [
        'email' =>  'require|email|token|unique:users',
        'username' => 'require|length:2,8|unique:users|regex:^[^\/\<\>\\\*\(\)]+$',
        'password' => 'regex:^[a-zA-Z0-9_\.]+$',
        'password2'=>'require|confirm:password'
    ];

}