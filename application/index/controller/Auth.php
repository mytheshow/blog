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
            //如果未激活跳转到一个unconfirmed.html，可以点击再次发送一封邮箱，可以防止第一封没收到
            if($res == -2){
                return view('unconfirmed');
            }
            if($res == 1){
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

    public function register(Request $request){
        if($request->isPost()){
                $data = $request->param();
                $validate = Loader::validate('Register');
                if(!$validate->check($data)){
                    $flashed_messages[] = $validate->getError();
                    $this->assign('flashed_messages',$flashed_messages);
                    return view('register');
                }
                $roles = db('roles');
                if($data['email']==config('BLOG_ADMIN')){
                    $role = $roles->where('name','Administrator')->find();
                }else{
                    $role = $roles->where('name','User')->find();
                }
                $user = model('Users');
                $user->data([
                    'username'=>$data['username'],
                    'email'=>$data['email'],
                    'password'=>md5($data['password']),
                    'create_time'=>date('Y-m-d H:i:s'),
                    'role_id'=>$role['id']
                ]);
                $res = $user->save();
                if($res){
                    $token = time().'|'.$data['email'];
                    //激活密令
                    $activecode = encryption($token);
                    //收件人邮箱
                    $toemail=$data['email'];
                    //发件人昵称
                    $name='php博客';
                    //邮件标题
                    $subject='请激活您的账户';
                    //邮件内容
                    $content="<h1>恭喜你，注册成功。</h1><a href=".$request->domain()."/active_user/$activecode>点击激活</a>";
                    //如果页面打印bool(true)则发送成功
                if(send_mail($toemail,$name,$subject,$content)){
                    $flashed_messages[] = '注册成功，请登陆邮箱激活';
                    $this->assign('flashed_messages',$flashed_messages);
                }
                }else{
                    $flashed_messages[] = '注册失败';
                    $this->assign('flashed_messages',$flashed_messages);
                }
        }

        return view();
    }
    public function ajax_email(Request $request){
        $email = $request->post('email');
        $user = Users::get(['email' => $email]);
        if ($user){
            echo json_encode(['valid'=>false]);
        }else{
            echo json_encode(['valid'=>true]);
        }
    }
    public function ajax_username(Request $request){
        $username = $request->post('username');
        $user = Users::get(['username' => $username]);
        if ($user){
            echo json_encode(['valid'=>false]);
        }else{
            echo json_encode(['valid'=>true]);
        }
    }

    public function active_user($activecode){
        $activecode = encryption($activecode,1);
        $arr = explode('|', $activecode);
        //把解密后的1509025674|123@qq.com拆分并赋值，见list函数
        if(count($arr)==2 && list($time, $email) = $arr){
            //1个小时过期
            if($time+3600 > time()){
                db('Users')->where('email',$email)->update(['confirmed'=>1]);
                $this->redirect('/login');
            }
        }

        $this->error('验证密令错误或过期,请重新登陆');
    }

    //再次发送激活邮件
    public function confirm(Request $request){
        if(session('?unconfirmed')){
            $data = Users::get(session('unconfirmed'));
            $token = time().'|'.$data['email'];
            //激活密令
            $activecode = encryption($token);
            //收件人邮箱
            $toemail=$data['email'];
            //发件人昵称
            $name='php博客';
            //邮件标题
            $subject='请激活您的账户';
            //邮件内容
            $content="<h1>恭喜你，注册成功。</h1><a href=".$request->domain()."/active_user/$activecode>点击激活</a>";
            if(send_mail($toemail,$name,$subject,$content)){
                session('unconfirmed',null);
                $flashed_messages[] = '一封新邮件已经发送';
                $this->assign('flashed_messages',$flashed_messages);
                return view('unconfirmed');
            }
        }
        abort(404);
    }

}