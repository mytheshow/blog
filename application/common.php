<?php
//cookie加密，保存账号进行自动登陆
function encryption($arg,$type=0){
    //制作盐
    $key=md5(config('SECRET_KEY'));
    if(!$type){
        return base64_encode($arg^$key);
    }
    //64位转码，然后异或对称加密
    $arg = base64_decode($arg);
    return $arg^$key;
}