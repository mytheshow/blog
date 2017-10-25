<?php
namespace app\index\controller;

class Index extends Common
{
//    protected $beforeActionList = [
//        'beforeAuth'
//    ];
    public function index()
    {

        return view('index');
    }
}
