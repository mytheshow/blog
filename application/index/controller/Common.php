<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
class Common extends Controller {
    protected function beforeAuth(){

        //当cookie存在session不存在时，自动登陆
        if(!is_null(cookie('auto')) && !session('?user_msg')) {
            $value = encryption(cookie('auto'), 1);
            $arr = explode('|', $value);
            //把解密后的wwwwww|127.0.0.1拆分并赋值，见list函数
            list($username, $ip) = $arr;
            $request = Request::instance();
            //如果ip异常就不会自动登陆
            if ($ip == $request->ip()) {
                //在登陆前先获取上次登陆的时间
                $userModel = db('Users');
                $user = $userModel->field('id,username,last_login_ip')->where('username', $username)->find();
                //更新登陆时间和ip
                $update = array(
                    'id'=>$user['id'],
                    'last_seen'=>date('Y-m-d H:i:s'),
                    'last_login_ip'=>$request->ip(1),
                );
                $userModel->update($update);
                //存入session
                $auth = array(
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'last_login_ip' => $user['last_login_ip']
                );
                session('user_msg', $auth);
            }
        }
        //检测session是否存在,session('user_msg')，存的是用户名和上次登陆的时间
        if(!session('?user_msg')){
            $this->redirect('/login');
            //redirect('Login/index')没有伪静态
            //redirect(url('Login/index'));等于$this->redirect()
        }
    }
}