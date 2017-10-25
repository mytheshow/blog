<?php
namespace app\index\Model;
use think\Model;


class Users extends Model {
    public function login($email, $password, $auto){
        $user = $this->where('email',$email)->find();
        if(md5($password)==$user['password']){
            $request = request();
            $update = array(
                'id'=>$user['id'],
                'last_seen'=>date('Y-m-d H:i:s'),
                'last_login_ip'=>$request->ip(1),
            );
            $this->update($update);
            //存入session
            $auth=array(
                'id' => $user['id'],
                'username' => $user['username'],
                'last_seen' => $user['last_seen']
            );
            session('user_msg',$auth);
            //将用户名加密写入cookie
            if($auto=="y"){
                cookie('auto',encryption($user['username'].'|'.$request->ip()),3600*24*30);
            }else{
                //删除cookie
                cookie('auto', null);
            }
            return 1;
        }else{
            return -1;
        }
    }
}