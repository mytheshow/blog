## 邮箱注册

> 参考博客【http://blog.csdn.net/u012995856/article/details/70196562】

 1. ``composer require phpmailer/phpmailer``
 2. 在common.php的创建发送邮箱的公共函数
 3. 在注册register和confirm中进行发送邮件
 
## 邮箱注册异步验证唯一

> 参考博客【https://segmentfault.com/a/1190000011732910】

## 修改Users模型的login()方法

```
            //如果邮件未激活
            if(!$user['confirmed']){
                session('unconfirmed',$user['id']);
                return -2 ;
            }
```

## 修改Auth的login()方法

```
            //如果未激活跳转到一个unconfirmed.html，可以点击再次发送一封邮箱，可以防止第一封没收到
            if($res == -2){
                return view('unconfirmed');
            }
```
## 在Auth控制器添加新方法

- register：注册成功后发送邮件，如果不是post提交显示注册表单进行注册用户

- ajax_email和ajax_username分别是异步验证邮箱和用户名唯一

- active_user是点击邮件激活用户，思路：解码出注册时间和邮箱，然后判断是否过期

- confirm是进行再次发送一封邮件，confirmed==0代表未激活，取出session的unconfirmed，
最后清除session，防止手动链接的方式进入该方法