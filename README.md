## 邮箱异步验证

 -  https://segmentfault.com/a/1190000011732910
 
 ## js验证几个注意点
 
 - ``verbose: false``,代表js验证合法后再异步后台验证，这样减少服务器压力
 - `` data: {}`` ,默认传递的就是输入框的值，所以一般不用写该属性，或者为空即可
 
 ## 后台注意点
 
 - 注意不是return而是echo
 - 返回json格式`` {'valid':true[,'message':'验证成功']}``