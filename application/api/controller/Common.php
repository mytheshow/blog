<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\Validate;
class Common extends Controller {
    protected $validater; // 用来验证数据/参数
    protected $params; // 过滤后符合要求的参数
    protected $request;
    //每个控制器的方法对应自己的验证条件，$rule = $this->rules[$this->request->controller()][$this->request->action()];
    protected $rules = array(
        'User' => array(
            'login' => array(
                'user_name' => ['require', 'chsDash', 'max' => 20],
                'user_pwd'  => 'require|length:32',
            ),
        ),
        'Code' => array(
            'get_code' => array(
                'username' => 'require',
                'is_exist' => 'require|number|length:1',
            ),
        ),
    );
    protected function _initialize(){
        parent::_initialize();
        $this->request = Request::instance();
       // $this->check_time($this->request->only(['time']));//最后要用die，用return会继续往下执行
       // $this->check_token($this->request->param());
        $this->params = $this->check_params($this->request->except(['time','token']));
    }

    public function check_time($arr){
        if(!isset($arr['time']) || intval($arr['time'])<=1){
            $this->return_msg(400,'时间戳不正确!');
        }
        if(time()-intval($arr['time'])>60){
            $this->return_msg(400,'请求超时!');
        }
    }

    public function return_msg($code,$msg='',$data=[]){
        $return_data['code'] = $code;
        $return_data['msg'] = $msg;
        $return_data['data'] = $data;
        echo json_encode($return_data);die;
    }

    public function check_token($arr){
        if(!isset($arr['token']) || empty($arr['token'])){
            $this->return_msg(400,'token不能为空');
        }
        $app_token = $arr['token']; // api传过来的token
        /*********** 服务器端生成token  ***********/
        unset($arr['token']);
        $service_token = '';
        foreach ($arr as $key => $value) {
            $service_token .= md5($value);
        }
        $service_token = md5('api_' . $service_token . '_api'); // 服务器端即时生成的token
        /*********** 对比token,返回结果  ***********/
        if ($app_token !== $service_token) {
            $this->return_msg(400, 'token值不正确!');
        }
    }

    /**
     * 验证参数 参数过滤
     * @param  [array] $arr [除time和token外的所有参数]
     * @return [return]      [合格的参数数组]
     */
    public function check_params($arr) {
        /*********** 获取参数的验证规则  ***********/
        $rule = $this->rules[$this->request->controller()][$this->request->action()];
        /*********** 验证参数并返回错误  ***********/
        $this->validater = new Validate($rule);
        if (!$this->validater->check($arr)) {
            $this->return_msg(400, $this->validater->getError());
        }
        /*********** 如果正常,通过验证  ***********/
        return $arr;
    }

    /**
     * 检测用户名并返回用户名类别
     * @param  [string] $username [用户名, 可能是邮箱, 也可能是手机号]
     * @return [string]           [检测结果]
     */
    public function check_username($username) {
        /*********** 判断是否为邮箱  ***********/
        $is_email = Validate::is($username, 'email') ? 1 : 0;
        /*********** 判断是否为手机  ***********/
        $is_phone = preg_match('/^1[34578]\d{9}$/', $username) ? 4 : 2;
        /*********** 最终结果  ***********/
        $flag = $is_email + $is_phone;
        switch ($flag) {
            /*********** not phone not email  ***********/
            case 2:
                $this->return_msg(400, '邮箱或手机号不正确!');
                break;
            /*********** is email not phone  ***********/
            case 3:
                return 'email';
                break;
            /*********** is phone not email  ***********/
            case 4:
                return 'phone';
                break;
        }
    }

    public function check_exist($value, $type, $exist) {
        $type_num  = $type == "phone" ? 2 : 4;
        $flag      = $type_num + $exist;
        $phone_res = db('user')->where('user_phone', $value)->find();
        $email_res = db('user')->where('user_email', $value)->find();
        switch ($flag) {
            /*********** 2+0 phone need no exist  ***********/
            case 2:
                if ($phone_res) {
                    $this->return_msg(400, '此手机号已被占用!');
                }
                break;
            /*********** 2+1 phone need exist  ***********/
            case 3:
                if (!$phone_res) {
                    $this->return_msg(400, '此手机号不存在!');
                }
                break;
            /*********** 4+0 email need no exist  ***********/
            case 4:
                if ($email_res) {
                    $this->return_msg(400, '此邮箱已被占用!');
                }
                break;
            /*********** 4+1 email need  exist  ***********/
            case 5:
                if (!$email_res) {
                    $this->return_msg(400, '此邮箱不存在!');
                }
                break;
        }
    }
}