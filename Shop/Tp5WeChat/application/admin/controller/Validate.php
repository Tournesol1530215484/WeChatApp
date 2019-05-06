<?php
namespace app\admin\controller;
use App\Controller\BaseController;
use think\Controller;
use think\Cache;

class Validate {

    function __construct()
    {

        $rule = [
            'name'  => 'require|max:25',
            'age'   => 'number|between:1,120',
            'email' => 'email',
        ];

        $msg = [
            'name.require' => '名称必须',
            'name.max'     => '名称最多不能超过25个字符',
            'age.number'   => '年龄必须是数字',
            'age.between'  => '年龄只能在1-120之间',
            'email'        => '邮箱格式错误',
        ];

        $data = [
            'name'  => 'thinkphp',
            'age'   => 10,
            'email' => 'thinkphp@qq.com',
        ];

    }


}