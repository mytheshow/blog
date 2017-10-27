<?php
namespace app\index\controller;

class Index extends Common
{
//    protected $beforeActionList = [
//        'beforeAuth'
//    ];
    public function index()
    {
        $this->assign('user_msg',session('user_msg'));
        return view('index');
    }
}
