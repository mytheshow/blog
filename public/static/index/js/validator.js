/**
 * Created by HONGXIN on 2017-10-23.
 */
$(function () {
    $('#regForm').bootstrapValidator({

        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },

        live: 'disabled',//验证失败后，提交按钮仍然是可选状态

        fields: {
            email: {
                message: '用户名验证失败',//默认
                verbose: false,
                validators: {
                    notEmpty: {
                        message: '邮箱不能为空'
                    },
                    emailAddress: {
                        message: '邮箱地址格式有误'
                    },
                    remote: {
                        url: '/ajax_email',
                        message:"此邮箱已经注册",
                        type: "post",
                        dataType: 'json',
                        data: {
                            //默认传递的就是输入框的值
                        },
                        delay: 500 //延迟效果
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: '邮箱地址不能为空'
                    },
                    stringLength: {
                        min: 6,
                        max: 18,
                        message: '用户名长度必须在6到18位之间'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_\.]+$/,
                        message: '密码由数字字母下划线和.组成'
                    }
                }
            },
            password2: {
                validators: {
                    notEmpty: {
                        message: '确认密码不能为空'
                    },
                    identical: {
                        field: 'password',
                        message: '两次密码必须一致'
                    }
                }
            },
            username:{
                validators: {
                    verbose: false,
                    notEmpty: {
                        message: '用户名不能为空'
                    },
                    stringLength: {
                        min: 2,
                        max: 8,
                        message: '用户名长度必须在2到8位之间'
                    },
                    remote: {
                        url: '/ajax_username',
                        message:"此用户名已经注册",
                        type: "post",
                        dataType: 'json',
                        data: {
                            //默认传递的就是输入框的值
                        },
                        delay: 500 //延迟效果
                    }
                }
            }
        }
    });

    $('#loginForm').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },

        live: 'disabled',

        fields: {
            email: {
                message: '用户名验证失败',
                validators: {
                    notEmpty: {
                        message: '用户名不能为空'
                    },
                    emailAddress: {
                        message: '邮箱地址格式有误'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: '邮箱地址不能为空'
                    },
                    stringLength: {
                        min: 6,
                        max: 18,
                        message: '用户名长度必须在6到18位之间'
                    }
                }

            }
        }
    });

});