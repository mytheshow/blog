<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\Model\Users;
use think\Loader;
class Auth extends Controller
{
    public function login(Request $request)
    {
        if($request->isPost()){
            $data = $request->param();
            $validate = Loader::validate('User');
            if(!$validate->check($data)){
                $flashed_messages[] = $validate->getError();
                $this->assign('flashed_messages',$flashed_messages);
                return view('login');
            }
            $user = new Users();
            $res = $user->login($data['email'], $data['password'], isset($data['remember_me'])? $data['remember_me']:'n');
            if($res > 0){
                $this->redirect('/');
            }
            $flashed_messages[] = '邮箱或密码错误';
            $this->assign('flashed_messages',$flashed_messages);
            return view('login');
        }
        //销毁session，注意：session(null, 'think');代表删除所以think创建的session文件
        session(null);
        return view('login');
    }

}