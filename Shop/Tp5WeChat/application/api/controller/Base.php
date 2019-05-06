<?php
namespace app\api\controller;
use think\Controller;
class Base extends Controller{



    /**
     * @param integer $code  [状态码]
     * @param string $msg    [提示信息]
     * @param array $data    [要返回的数据]
     * @return json return   [返回的数据类型]
     */
    public function return_msg($code,$msg='',$data=[]){
        /** 接收数组进行**/
        $return_msg['code']=$code;
        $return_msg['msg']=$msg;
        $return_msg['data']=$data;
        echo json_encode($return_msg);die;
    }

    /**
     * @return 验证openid
     *
     */
    public function CheckOpendid($code,$openid){

        $appid=config('appid');
        $secret=config('secret');
        $js_code=$code;
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

        if($obj->openid==$openid){
            return true;
        }else{
            return false;
        }

    }


    /**
     * 重置token
     */

    public function restToken($openid,$nickName,$avatarUrl,$province){

        $tonken=$this->createNoncestr(32);
        $tonken_time=date(time());
       $res= db('user')
            ->where('openid', $openid)
            ->update(['tonken' => $tonken,'tonken_time'=>$tonken_time,'nike_name'=>$nickName,'head_src'=>$avatarUrl,'province'=>$province]);
        if($res){
            return true;
        }else{
            return false;
        }

    }

    /**
     * 获取随机字符串
     */

    protected  function  createNoncestr($length){
        $chars="abcdefghijklmnopqrstuvwxyz0123456789";
        $str="";
        for($i=0;$i<$length;$i++){
            $str.=substr($chars,mt_rand(0,strlen($chars)-1),1);
        }
        return $str;
    }

    //验证token过期时间

    public  function checktoken($token){
        if(time()-$token>3600*24*7 || time()-$token <0){

            return false;
        }else{
            return true;
        }
    }


}