<?php
namespace app\admin\Model;
use think\Model;
class Goods extends Model{

    //商品的后置操作

    protected static function init(){
        Goods::event('afrer_insert',function($goods){


        });
    }

        //获取器
    public function getStatusAttr($value)
    {
        $status = [-1=>'删除',0=>'禁用',1=>'正常',2=>'待审核'];
        return $status[$value];
    }




}