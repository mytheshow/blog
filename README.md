> 以下是接口开发的学习，与本项目无关，在git上面单独添加提交标签，该项目完毕后会添加本项目的api

## 添加接口

1. 添加二级域名api.blog.com
2. 添加api模块，在控制器下建User.php，并建了login()方法，修改路由``Route::post('/user', 'api/user/login');``

## common.php创建api过滤条件

1. 超时过滤，前端传过来的时间与当前时间对比，大于60秒为超时
2. token过滤，前端穿过来的token，与服务端的token对比
3. token算法，把前端传过来的参数unset掉token后进行逐个拼接md5加密
4. 以上的超时和token通过后，验证传过来的参数是否合法(如：特殊字符)

此时的_initialize()

```
    protected function _initialize(){
        parent::_initialize();
        $this->request = Request::instance();
       // $this->check_time($this->request->only(['time']));//最后要用die，用return会继续往下执行
       // $this->check_token($this->request->param());
        $this->params = $this->check_params($this->request->except(['time','token']));
    }
```

## 验证码接口

1. 创建Code控制器
2. 在Common.php判断传过来的是邮箱还是手机

### 创建迁移数据库脚本


``php think migrate:create InterfaceUser``

由于是学习，所以把database.php的表前缀改一下(api_)，以至于来和项目的表区分