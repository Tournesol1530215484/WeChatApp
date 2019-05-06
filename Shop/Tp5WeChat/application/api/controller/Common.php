<?php
namespace app\api\controller;
use think\Controller;

class Common extends Base{

    public function CheckOpendid($code,$openid){

        $appid=config('appid');
        $secret=config('secret');
        $js_code=input('js_code');
        $openid=input('openid');
        $grant_type=input('authorization_code');
        $url="https://api.weixin.qq.com/sns/jscode2session";
        $data=[
            'appid'=>$appid,
            'secret'=>$secret,
            'js_code'=>$js_code,
            'grant_type'=>$grant_type
        ];

        $res=httpRequest($url,'POST',$data);
        $obj=json_decode($res);
        if($obj.$openid==$openid){
            return true;
        }else{
            return false;
        }

    }


}