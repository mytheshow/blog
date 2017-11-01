<?php
namespace app\index\controller;

class Index extends Common
{
//    protected $beforeActionList = [
//        'beforeAuth'
//    ];

    public function index()
    {
        $user_msg = db('users')->where('id',session('uid'))->find();
        $this->assign('user_msg',$user_msg);
        return view('index');
    }

    public function user($username){
        if(session('uid')){
            $user_msg = db('users')->where('id',session('uid'))->find();
            $user_msg['last_login_ip'] = long2ip(cookie('last_login_ip'));
            $user_msg['last_seen'] = long2ip(cookie('last_seen'));
            $this->assign('user_msg',$user_msg);
            return view();
        }
        abort(404);
    }
}
