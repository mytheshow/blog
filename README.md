### 开启调试模式

aoolication下config.php下`` 'app_debug'=> true,``

### 404页面

关闭上面的调试模式`` 'app_debug'=> false,``然后添加配置

```
    'http_exception_template'    =>  [
        // 定义404错误的重定向页面地址
        404 =>  APP_PATH.'404.html',
        // 还可以定义其它的HTTP status
        //401 =>  APP_PATH.'401.html',
    ],
```
然后再application下面创建404.html，如果没有创建改模版将会
使用默认的``'exception_tmpl'``

### 配置模版常量

在config.php中找到``// 视图输出字符串内容替换  'view_replace_str' ``

```
    'view_replace_str'       => [
        '__PUBLIC__'=>'/public/',
        '__ROOT__' => '/',
        '__INDEX__'=>'/public/static/index',
        '__MODULE__'=>'/public/index'
    ],
```
### 配置普通常量

 1. 在入口文件定义``define('NOW_TIME', time())``，此时在整个项目都可以使用
 2. 在config.php中``define('NOW_TIME', time())``，此时在application下的模块中使用
 
 > 使用案例：
 
 ```
     public function setCreateAttr()
     {
         return NOW_TIME;
     }
 ```
  3. 在config.php中用键值对配置``'COOKIE_KEY' => 'www.51aixue.cn'``
  
  > 使用案例
  
  ```
  $key=md5(config('COOKIE_KEY'));
  ```
  
  > 配置文件部分参考
  
  ```
  <?php
  // +----------------------------------------------------------------------
  // | ThinkPHP [ WE CAN DO IT JUST THINK ]
  // +----------------------------------------------------------------------
  // | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
  // +----------------------------------------------------------------------
  // | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
  // +----------------------------------------------------------------------
  // | Author: liu21st <liu21st@gmail.com>
  // +----------------------------------------------------------------------
  
  return [
      // +----------------------------------------------------------------------
      // | 模板设置
      // +----------------------------------------------------------------------
  
      'template'               => [
          // 模板引擎类型 支持 php think 支持扩展
          'type'         => 'Think',
          // 模板路径
          'view_path'    => APP_PATH.'index/view/default/',
          // 模板后缀
          'view_suffix'  => 'html',
          // 模板文件名分隔符
          'view_depr'    => DS,
          // 模板引擎普通标签开始标记
          'tpl_begin'    => '{',
          // 模板引擎普通标签结束标记
          'tpl_end'      => '}',
          // 标签库标签开始标记
          'taglib_begin' => '{',
          // 标签库标签结束标记
          'taglib_end'   => '}',
      ],
  
      'view_replace_str'  =>  [
          '__PUBLIC__'=>'/public/',
          '__ROOT__' => '/',
          '__INDEX__'=>'/public/static/index',
          '__MODULE__'=>'/public/index'
      ],
      // +----------------------------------------------------------------------
      // | 自定义常量
      // +----------------------------------------------------------------------
      define('NOW_TIME', time()),
      'COOKIE_KEY' => 'www.51aixue.cn',
  
  ];

  ```
  
### bootstrapvalidator验证表单
  
> 【http://blog.csdn.net/u013938465/article/details/53507109】和【http://www.cnblogs.com/yeyublog/p/6672838.html】

### 路由配置

 -  ``'url_route_on'``设置为true，开启路由
 - `` 'route_complete_match'``，路由完整匹配，这个手册上讲的可以看懂，不再举例
 - ``  'url_route_must' ``，强制路由，表示常规的"模块/控制器/操作方法/参数"将不可用，必须设置路由
 
 > 路由配置：【http://www.jianshu.com/p/07aa77f47995】
 
### 创建数据库迁移

例子：

> 创建迁移脚本

```
php think migrate:create CreatUsers
```
> 创建表

```
public function up()
    {
        $table = $this->table('users',array('engine'=>'MyISAM'));
        $table->addColumn('username', 'string', ['limit' => 16, 'null' => false])
            ->addColumn('email', 'string', ['limit' => 32, 'null' => false])
            ->addColumn('password', 'string',array('limit' => 32,'default'=>md5('123456'),'comment'=>'用户密码'))
            ->addColumn('confirmed','integer',['limit' => 1, 'default' => 0, 'comment' => '邮件是否确认'])
            ->addColumn('last_login_ip', 'integer',array('limit' => 11,'default'=>0,'comment'=>'最后登录IP'))
            ->addColumn('last_seen', 'datetime',array('default'=>0,'comment'=>'最后登录时间'))
            ->addColumn('create_time', 'datetime',array('default'=>0,'comment'=>'最后登录时间'))
            ->addIndex(array('email'), array('unique' => true))
            ->create();
    }
```
> 添加/删除字段

添加和删除字段，都是新建一个脚本然后使用save()方法,`` php think migrate:create AddColum ``

```
    public function up()
    {
        $table = $this->table('users',array('engine'=>'MyISAM'));
        $table->addColumn('name', 'string', ['limit' => 16, 'null' => false])
              ->save();
    }
```

执行`` php think migrate:run ``后只执行新创建脚本(多个)的up()方法

> 回滚,更详细操作【https://tsy12321.gitbooks.io/phinx-doc/commands.html】

```
php think migrate:rollback #回滚最近一次操作
php think migrate:rollback -t 0 #回滚所有操作，注意不想回滚的用断点保护
php think migrate:status #查看状态
```
### 关于application/common.php

在此公共文件中创建的方法，可以直接在整个应用的所有控制器中使用(admin或者index模块登)

### 跨站请求伪造的token验证

参见：TP5手册token令牌

### ip2long和long2ip

在进行ipv4地址存储的时候一般存储它的数字来节省空间$request->ip(1),这个"1"代表转换为数字
使用的是php的ip2long函数，long2ip可以转换回原来的ip。【ip to 长整型】

### 关于ORM和db()

``Db::table('表名') == db('表名')``，只能执行数据库操作(增删改查)，不能调用对象的方法，它是TP3.2的``M('表名')``

### 清除session和清除cookie

 1. //销毁session，注意：session(null, 'think');代表删除所以think创建的session文件,主要在app\index\Controller\login()删除session
 
 ```
        session(null);     
 ```
  2.  //删除cookie,主要在app\index\model\Users中的login方法清除cookie实现删除记住我``remember_me``
  
 ```                    
  cookie('auto', null);
 ``` 
 
 > 2017年10月26日 01:55:59