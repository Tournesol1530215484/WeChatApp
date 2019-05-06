<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/29 0029
 * Time: 下午 2:18
 */
namespace app\api\controller;
use think\Controller;
class Wepay extends Base{
    //下单
    public function submitOrder($uid,$token,$totalprice, $openid, $Carid,$Addresid){
        //验证token
        if($this->checktoken($token)){
            if($uid){
                $res=$this->doOrder($uid,$openid,$Carid,$Addresid,$totalprice);//处理基础数据
                $res['order']=db('weipay_records')->where('out_trade_no',$res['out_trade_no'])->find();
                $res['data']=$this->getWexinInfo($res['out_trade_no']);

            }

            $this->return_msg(200,"成功",$Addresid);
        }else{
            $this->return_msg(400,"token过期");
        }


    }


    /**
     * 处理微信的订单信息
     * @param $outtradeno  订单号
     */
    public function getWexinInfo($outtradeno){
        $order=db('weipay_records')->where('out_trade_no',$outtradeno)->find();//获取订单信息
        $goodsid=db('odergoods')->where('orderid',$order['id'])->order('goodsprice desc')->limit(1)->value('goodsid');//根据订单查出商品id
        $Body=db('goods')->where('id',$goodsid)->value('goods_name');
        $out_trade_no=$outtradeno;
        $total_fee=$order['total_fee']*100;
        $openid=$order['openid'];
        $response=$this->getPrePayOrder($Body,$out_trade_no,$total_fee,$openid);//调用统一支付接口

        $this->return_msg(200,"下单成功",$response);

        $this->getwxPayOrder($response);




    }

    /**
     * @param $uid      用户id
     * @param $openid   用户openid
     * @param $Carid    购物车id
     * @param $Addresid 收货地址id
     * @return array    返回的是订单号
     */
    public function doOrder($uid,$openid,$Carid,$Addresid,$totalprice){
        //获取收货地址
        $addres=db('addres')->find($Addresid);
        //写入数据表
        $data=array(
            'out_trade_no'=>$this->createNoncestr(),
            'uid'=>$uid,
            'consignee'=>$addres['consignee'],
            'addres'=>$addres['addres'],
            'phone'=>$addres['phone'],
            'openid'=>$openid,
            'total_fee'=>$totalprice
        );

        //写入订单表
        $orderid=db('weipay_records')->insertGetId($data);

        //写入订单商品表
        $carlist=db('cart')->where('id','in',$Carid)->select();
        $goods=db('goods');
        foreach($carlist as $k=>$v){
            $datas=array(
                'orderid'=>$orderid,
                'goodsid'=>$v['goodsid'],
                'goodsnum'=>$v['num'],
                'goodsprice'=>$goods->where('id',$v['goodsid'])->value('shop_price')
            );
            db('odergoods')->insert($datas);
        }

        //返回订单号
        return array('out_trade_no'=>$data['out_trade_no']);
    }


    /**
     * @param int $lengyh 随机字符串的长度默认是32位
     * @return string   返回随机字符串
     *
     */
    public function createNoncestr($lengyh=32){
        $chars="abcdefghijklmnopqrstuvwxyz0123456789";
        $str='';
        for($i=0;$i<$lengyh;$i++){
            $str.=substr($chars,mt_rand(0,strlen($chars)-1),1);
        }
        return $str;
    }




    //========================================统一支付接口代码====================================

    //调用统一微信下单
    public function getPrePayOrder($body,$out_trade_no,$total_fee,$openid){
        //商品描述 body
        //随机字符串 nonce_str
        //签名 	sign
        //商户订单号 out_trade_no
        //总金额 total_fee
        //终端ip spbill_create_ip
        //交易起始时间    time_start
        //交易结束时间     	time_expire
        //通知地址      notify_url
        //交易类型 	trade_type


        //调用微信小程序支付统一下单地址
        $url="https://api.mch.weixin.qq.com/pay/unifiedorder";
        $nonce_str=$out_trade_no;
        $wxpayconf=config('config');
        $notify_url=$wxpayconf["notify_url"];


        //把数据进行接收
        $data=[
            'body'=>$body,
            'out_trade_no'=>$out_trade_no,
            'nonce_str'=>$this->createNoncestr(),
            'total_fee'=>$total_fee,
            'spbill_create_ip'=>$this->getSpbillip(),
            'openid'=>$openid,
            'trade_type'=>'JSAPI'
        ];
        $sign=$this->getSign($data);
        $data['sign']=$sign;

        //将微信返回的xml转成数组
        $xml=$this->arrayToXml($data);
        $response=$this->postXmlCurl($xml,$url);
        //将微信返回的结果转成数组
        $response=$this->xmlToArray($response);
        return $response;
    }



    //微信支付接口
    public function getwxPayOrder($response){
        if($response===false){
            echo 'FALSE';
            exit;       //如果解析结果为空，终止程序
        }

        if($response['return_code']=='FAIL'){
            echo $response->return_msg;
            exit;
        }
        else{
            //调用微信支付
            $resignData=array([
                'appId'=>$response['appid'],
                'nonceStr'=>$response['nonceStr'],
                'partnerid'=>$response['mch_id'],
                'noncestr' =>$response['nonce_str'],
                'nocce_str'=>$response['out_trade_no'],
                'timestamp'=>time(),
                'mweb_url'=> $response['mweb_url'],
                'package'=>'Sign=WXPay'

            ]);
            $sign=$this->getSign($resignData);
            $resignData['sign']=$sign;
            $res['list']=$resignData;
            $this->return_msg(200,"下单成功",$res);
        }


    }

    /**
     * 将xml转化为数组
     * @param $res
     *
     */
    public function  xmlToArray($xml){
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;

    }

    /**
     * 以post方式提交到微信支付系统
     * @param $xml
     * @param $url
     * @param int $second
     */

    public  function postXmlCurl($xml,$url,$second=30){
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果

        if($data){
            curl_close($ch);
            return $data;
        }else{
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error"."<br>";
            curl_close($ch);
            return false;
        }

    }


    /**
     * @param $arr  需要转换的数组
     * @return string   返回的值
     */
    public function arrayToXml($arr){
        $xml = "<xml>";
        foreach ($arr as $key=>$val){
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }


    /**
     * @param $Obj  传进来的参数
     * @return string   返回签名
     */
    public function getSign($Obj){
        $wxpayconf=config('config');
        foreach($Obj as $k=>$v){
            $Parameters[$k]=$v;
        }
        //步骤一：按字典排序参数
        ksort($Parameters);
        $String=$this->formatBizQueryParaMap($Parameters,false);
        //签名步骤二：在string后加入KEY
        $String=$String."&key=".$wxpayconf['api_key'];
        //签名步骤三：MD5加密
        $String=MD5($String);
        //签名步骤四：所有字符转为大写
        $result_=strtoupper($String);
        return $result_;

    }

    /**
     * 格式化参数，签名过程需要使用
     * @param $paraMap
     * @param $urlencode
     */

    public  function  formatBizQueryParaMap($paraMap,$urlencode){
        $buff='';
        ksort($paraMap);
        foreach($paraMap as $k=>$v){
            if($urlencode){
                $v=$urlencode($v);
            }
            $buff.=$k."=".$v."&";
        }
        $reqPar="";
        if(strlen($buff)>0){
            $reqPar=substr($buff,0,strlen($buff)-1);
        }
        return $reqPar;
    }


    /**
     * 获取当前服务器ip
     *
     * @return string   返回ip地址
     */
    public function getSpbillip(){
            if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')) {

                $ip = getenv('HTTP_CLIENT_IP');

            } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')) {

                $ip = getenv('HTTP_X_FORWARDED_FOR');

            } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'),'unknown')) {

                $ip = getenv('REMOTE_ADDR');

            } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {

                $ip = $_SERVER['REMOTE_ADDR'];

            }
            return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
    }





}