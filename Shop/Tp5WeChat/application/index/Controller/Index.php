<?php
namespace app\index\controller;
use think\Controller;
use think\Cache;

class Index extends Controller
{
    function redis(){
    	phpinfo();
    	// $option=[
    	// 	'type'=>'redis',
    	// 	'password'=>'123456',
    	// 	'prefix'=>'sbn-',
    	// 	'host'=>'127.0.0.1',
    	// ];

    	// Cache::init($option);
    }
}
