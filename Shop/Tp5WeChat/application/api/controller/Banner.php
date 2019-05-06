<?php
namespace app\api\controller;
use app\api\Model\Goodsphoto;
use app\api\Model\Goods;
use Catetree\Catetree;
use think\Controller;
use think\Request;
use think\Db;

class Banner extends Base{

    /**
     * 查询所有的Banner
     */

    public  function getAllBanner(){
        //$banner=db('banner')->where('type','=',0)->order('scort DESC')->limit(3)->select();
        $banner=model('banner')->where('type','=',0)->order('scort DESC')->limit(3)->select();
        if($banner){
            $this->return_msg(200,"数据查询成功",$banner);
        }else{
            $this->return_msg(400,"数据查询失败",$banner);
        }
    }


    /**
     * 查询所有的顶级cate
     */

    public function gettopCate(){
        $cate=model('cate')->where(['show_nav'=>1,'pid'=>0])->order('sort DESC')->select();
        if($cate){
            $this->return_msg(200,"数据查询成功",$cate);
        }else{
            $this->return_msg(400,"数据查询失败",$cate);
        }
    }


    /**
     * 获取当前栏目信息
     */
    public function getCurrentCate($id){
        $cate=model('cate')->find($id);
        if($cate){
            //return json_encode($cate);
            $this->return_msg(200,"数据查询成功",$cate);
        }else{
            //return json_encode('error');
            $this->return_msg(400,"数据查询失败",$cate);
        }
    }

    /**
     * 获取当前栏目下所有子栏目以及信息
     *
     */

    public function getCateChild($pid){

        $cates=model('cate')->field('id,pid,images,cate_name')->order('sort DESC')->where('pid','=',$pid)->select();
        if($cates){
            //return json_encode($cate);
            $this->return_msg(200,"数据查询成功",$cates);
        }else{
            //return json_encode('error');
            $this->return_msg(400,"数据查询失败",$cates);
        }

    }


    /**
     * 获取所有的同级栏目
     */

    public function getBrotherCate($gid){
        //获取该栏目的上级栏目
        $pid=db('cate')->field('pid,description')->find($gid);
        $cates=db('cate')->field('id,cate_name,images')->where(['pid'=>$pid['pid'],'show_nav'=>1])->order('sort asc')->select();
        $data['cates']=$cates;
        $data['description']=$pid['description'];
        if($cates){
            $this->return_msg(200,"数据查询成功",$data);
        }else{
            $this->return_msg(400,"数据查询失败",$data);
        }


    }

    /**
     *
     * 获取该栏目下所有的商品
     */
    public function getCateGoods($gid,$page){
//        $gid=69;
//        $page=input('page',1);
        $config=['page'=>$page,'list_rows'=>4];
        $goodsModel=new Goods();
        $goods=$goodsModel->where('category_id','=',$gid)->paginate(null,false,$config);
        if($goods){
            $this->return_msg(200,"数据查询成功",$goods);
        }else{
            $this->return_msg(400,"数据查询失败",$goods);
        }
    }


    /**
     * 查询商品详情信息
     */

    public function GoodsInfo($cid){
//        $cid=83;
        $goodsModel=new Goods();
        $goodsPtotoModel=new Goodsphoto();
        $goods=$goodsModel->find($cid);

        //获取图片高度
        if(count($goods['big_thumb'])>0){
           $ext = strrchr($goods['big_thumb'],'.');
            switch ($ext){
                case '.gif':
                    $img = imagecreatefromgif($goods['big_thumb']);
                    $goods['big_thumb_height']=imagesy( $img );
                    break;
                case '.png':
                    $img = imagecreatefrompng($goods['big_thumb']);
                    $goods['big_thumb_height']=imagesy( $img );
                    break;
                case '.jpeg':
                    $img = imagecreatefromjpeg($goods['big_thumb']);
                    $goods['big_thumb_height']=imagesy( $img );
                    break;

                case '.jpg':
                    $img = imagecreatefromjpeg($goods['big_thumb']);
                    $goods['big_thumb_height']=imagesy( $img );
                    break;
//                $imgs=$img_info=getimagesize($goods['big_thumb']);
//                $img = $imgs[1];
                default:
                    $goods['big_thumb_height']=0;
                    break;

            }

        }else{
            $goods['big_thumb_height']=0;
        }
        $goods['og_photo']=$goodsPtotoModel->where('goodsid','=',$cid)->select();
        if($goods){
            $this->return_msg(200,"数据查询成功",$goods);
        }else{
            $this->return_msg(400,"数据查询失败",$goods);
        }
    }


    //商品收藏管理
    public  function GoodsCollection($uid,$goodsid,$token){
        $status='-1';
        //验证token
        if($this->checktoken($token)){

            $res=db('collection')->where(array('goodsid'=>$goodsid))->find();

            if($res){   //取消收藏
                db('collection')->where(['uid'=>$uid,'goodsid'=>$goodsid])->delete();
                $this->return_msg(200,"已取消",$status);
            }else{      //收藏
                db('collection')->insert(['uid'=>$uid,'goodsid'=>$goodsid]);
                $status='1';
                $this->return_msg(200,"已收藏",$status);
            }

        }else{
            $this->return_msg(400,"登陆过期，请重新登陆");
        }

    }


    //判断商品是否已被该用户收藏

    public function isCollection($uid,$goodsid){
        $status='-1';
        $res=db('collection')->where(array('goodsid'=>$goodsid,'uid'=>$uid))->find();
        if($res){
            $status='1';
            $this->return_msg(200,"已收藏",$status);
        }else{
            $this->return_msg(200,"未收藏",$status);
        }
    }



    //商品加入购物车

    public function AddToCart($uid,$goodsid,$token,$Sales){
        if($this->checktoken($token)){
            //获取传过来的$Sales，如果大于库存，就提示库存不足
            $goods=db('goods')->where(array('id'=>$goodsid))->find();
            if($goods['stock']>=$Sales){
                //判断用户是否已经加入过库存了
                $res=db('cart')->where(array('goodsid'=>$goodsid,'uid'=>$uid))->find();
                if($res){   //已经加入过购物车，直接修改数据即可
                    $res['num']=$res['num']+$Sales;
                   db('cart')->where(array('goodsid'=>$goodsid,'uid'=>$uid))->update(['num' => $res['num']]);
                    $this->return_msg(200,"加入购物车成功");
                }else{      //未加入过购物车，创建新数据

                    $data=['goodsid'=>$goodsid,'uid'=>$uid,'num'=>$Sales];
                    db('cart')->insert($data);
                    $this->return_msg(200,"加入购物车成功");
                }

            }else{
                $this->return_msg(400,"库存不足");
            }

        }else{
            $this->return_msg(400,"登陆过期，请重新登陆");
        }
    }



    //获取购物车信息
    public function ShopCart($uid,$token){
        if($this->checktoken($token)){
            if($uid){
                $carlist=db('cart')->where(array('uid'=>$uid))->order('uid desc')->select();
                $sql="SELECT selected, count(1) AS counts FROM wx_cart GROUP BY selected";
                $num=Db::query($sql);
                $resultstatus=false;
                $nums=0;
                foreach($num as $k =>$v){
                    if($v['selected']==1){
                        $nums=$v['counts'];
                    }
                }
                if($nums===count($carlist)){
                    $resultstatus=true;
                }
                if($carlist){
                    foreach($carlist as $k=>$v){
                        $goodsmodel=new Goods();
                        $goodsinfo=$goodsmodel->field('goods_name,og_thumb,shop_price')->find($v['goodsid']);
                        $goodsinfo=$goodsinfo->toArray();
                        $carlist[$k]=array_merge($goodsinfo,$v);
                    }

                    $data['carlist']=$carlist;
                    $data['resultstatus']=$resultstatus;
                    $this->return_msg(200,"数据获取成功",$data);

                }else{
                    $this->return_msg(400,"数据获取失败");
                }
            }else{
                $this->return_msg(400,"登陆过期，请重新登陆");
            }


        }else{
            $this->return_msg(400,"登陆过期，请重新登陆");
        }
    }


    //修改购物车的选择信息


    public function ShopCartSelect($uid,$token,$goodsid){
        if($this->checktoken($token)){

            $res=db('cart')->where(['uid'=>$uid,'goodsid'=>$goodsid])->field('selected')->find();
            if($res){
                if($res['selected']==1){
                    db('cart')->where(['uid'=>$uid,'goodsid'=>$goodsid])->update(['selected'=>0]);
                    //计算是否全部选中
                    $carlist=db('cart')->where(array('uid'=>$uid))->order('uid desc')->select();
                    $sql="SELECT selected, count(1) AS counts FROM wx_cart GROUP BY selected";
                    $num=Db::query($sql);
                    $resultstatus=false;
                    $nums=0;
                    foreach($num as $k =>$v){
                        if($v['selected']==1){
                            $nums=$v['counts'];
                        }
                    }
                    if($nums===count($carlist)){
                        $resultstatus=true;
                    }

                    $this->return_msg(200,"数据获取成功",$resultstatus);
                }else{
                    db('cart')->where(['uid'=>$uid,'goodsid'=>$goodsid])->update(['selected'=>1]);
                    $carlist=db('cart')->where(array('uid'=>$uid))->order('uid desc')->select();
                    $sql="SELECT selected, count(1) AS counts FROM wx_cart GROUP BY selected";
                    $num=Db::query($sql);
                    $resultstatus=false;
                    $nums=0;
                    foreach($num as $k =>$v){
                        if($v['selected']==1){
                            $nums=$v['counts'];
                        }
                    }
                    if($nums===count($carlist)){
                        $resultstatus=true;
                    }
                    $this->return_msg(200,"数据获取成功",$resultstatus);
                }

            }else{
                $this->return_msg(400,"数据获取失败");
            }


        }else{
            $this->return_msg(400,"登陆过期，请重新登陆");
        }
    }

    //购物车全选功能


    public function ShopCartSelectAll($uid,$token,$selectAll){
        if($this->checktoken($token)){
            $res=db('cart')->where(['uid'=>$uid])->select();
            if($res){
                if($selectAll==='false'){
                    foreach($res as $k=>$v){
                        db('cart')->where(['id'=>$v['id']])->update(['selected'=>0]);
                    }
                    $this->return_msg(200,"数据获取成功",$selectAll);

                }else{
                    foreach($res as $k=>$v) {
                        db('cart')->where(['id' => $v['id']])->update(['selected' => 1]);
                    }
                    $this->return_msg(200,"数据获取成功",$selectAll);
                }

            }else{
                $this->return_msg(400,"数据获取失败");
            }
        }else{
            $this->return_msg(400,"登陆过期，请重新登陆");
        }
    }

        public function test(){
            $uid=1;
            $goodsid=84;
            //$res=db('cart')->where(['uid'=>$uid,'goodsid'=>$goodsid])->find();
            $rews=db('cart')->where(['uid'=>$uid,'goodsid'=>$goodsid])->update(['num'=>13]);
            dump($rews);
        }

    //购物车商品的数量修改
    public function Goodsnum($uid,$token,$goodsid,$num){

        if($this->checktoken($token)){
            if($num<=1){
                $this->return_msg(400,"最少选中1件");
            }else if($num>20){
                $this->return_msg(400,"最多只能购买20件");
            }else{
                db('cart')->where(['uid'=>$uid,'goodsid'=>$goodsid])->update(['num'=>$num]);
                $this->return_msg(200,"修改数据成功");
            }

        }else{
            $this->return_msg(400,"登陆过期，请重新登陆");
        }
    }

    //购物车数据删除
    public function delGoodsCart($uid,$token,$goodsid){
        if($this->checktoken($token)){
            db('cart')->where(['uid'=>$uid,'goodsid'=>$goodsid])->delete();
            $res=db('cart')->where(['uid'=>$uid])->select();
            if($res){
                $this->return_msg(201,"以清空购物车");
            }
            $this->return_msg(200,"成功移除购物车");
        }else{
            $this->return_msg(400,"登陆过期，请重新登陆");
        }
    }



    //计算购物车总价

//    public  function ShopCartAllprice($uid,$token){
//
//        if($this->checktoken($token)){
//
//            db('')
//
//        }else{
//            $this->return_msg(400,"登陆过期，请重新登陆");
//        }
//
//    }


    //修改购物车的数量
    public function ShopCartnum($uid,$token,$goodsid,$num){
        if($this->checktoken($token)){
            db('cart')->where(['goodsid'=>$goodsid,'uid'=>$uid])->update(['num' => $num]);

                $this->return_msg(200,"修改成功",$num);

        }else{
            $this->return_msg(400,"登陆过期，请重新登陆");
        }
    }


    //立即购买
    public function BuyNow($uid,$goodsid,$token,$Sales){
            if($this->checktoken($token)){
                //获取传过来的$Sales，如果大于库存，就提示库存不足
                $goods=db('goods')->where(array('id'=>$goodsid))->find();
                if($goods['stock']>=$Sales){
                    //判断用户是否已经加入过库存了
                    $res=db('cart')->where(array('goodsid'=>$goodsid,'uid'=>$uid))->find();
                    if($res){   //已经加入过购物车，直接修改数据即可
                        $res['num']=$res['num']+$Sales;
                        db('cart')->where(array('goodsid'=>$goodsid,'uid'=>$uid))->update(['num' => $res['num']]);
                        $this->return_msg(200,"加入购物车成功");
                    }else{      //未加入过购物车，创建新数据

                        $data=['goodsid'=>$goodsid,'uid'=>$uid,'num'=>$Sales];
                        db('cart')->insert($data);
                        $this->return_msg(200,"加入购物车成功");
                    }
                }else{
                    $this->return_msg(400,"库存不足");
                }

            }else{
                $this->return_msg(400,"登陆过期，请重新登陆");
            }
    }



    //---------------------------about----------------------------------


    //获取用户所有的收藏商品信息

    public function getAllCollection($uid,$token){
        if($this->checktoken($token)){
            $goods=db('collection')->where(['uid'=>$uid])->select();
            if(count($goods)==0){
                $this->return_msg(201,"您的收藏商品为空");
            }else{
                $goodsmodel=new Goods();
                $Collectionlist=array();
                foreach($goods as $k=> $v){
                    $Collectionlist[]=$goodsmodel->where(['id'=>$v['goodsid']])->find();
                }
                $this->return_msg(200,"数据获取成功",$Collectionlist);
            }
        }else{
            $this->return_msg(400,"数据获取失败");
        }
    }

    //删除用户收藏商品
    public function Rmcollection($uid,$token,$gid){
        if($this->checktoken($token)){
            db('collection')->where(['uid'=>$uid,'goodsid'=>$gid])->delete();
            //判断该用户是否为空
            $sql="SELECT uid, count(1) AS counts FROM wx_cart GROUP BY uid ";
            $num=Db::query($sql);
            $nums=true;
            foreach($num as $k=>$v){
                if($v['uid']==$uid && $v['counts']>=1){
                    $nums=false;
                }
            }

            $this->return_msg(200,"成功移除",$nums);

        }else{
            $this->return_msg(400,"数据获取失败");
        }
    }




    //==================================用户收货地址管理============================
    //添加用户收获地址
    public function Addaddres($uid,$token,$consignee,$addres,$phone,$check){
        $date['consignee']=$consignee;
        $date['addres']=$addres;
        $date['phone']=$phone;
        $date['check']=$check;
        if($this->checktoken($token)){
           // isset($date['check'])?($date['check'] ? $date["check"] = 1 : $date["check"] = 0):$date["check"] = 0;
            $date['check'] ? $date["check"] = 1 : $date["check"] = 0;
            $date['uid']=$uid;
            //如果没有收货地址，新增用户收货地址
            $res=db('addres')->where(['uid'=>$uid])->select();
            if(count($res)==0){ //没有值，直接插入
                db('addres')->insert($date);
                $this->return_msg(200,"添加成功");
            }else{  //有值，判断是否是默认
                if($date['check']==1){
                    foreach($res as $k=> $v){
                        db('addres')->where(['id'=>$v['id']])->update(['check'=>0]);
                    }
                    db('addres')->insert($date);
                    $this->return_msg(200,"添加成功");
                }else{
                    db('addres')->insert($date);
                    $this->return_msg(200,"添加成功");
                }
            }

        }else{
            $this->return_msg(400,"token已过期");
        }

    }


    //查询所有的用户收获地址
    public function Lisaddres($token,$uid){
        if($this->checktoken($token)){
            $data=db('addres')->where(['uid'=>$uid])->select();

            $this->return_msg(200,"查询成功",$data);
        }else{
            $this->return_msg(400,"token已过期");
        }
    }

    //根据id查出用户的收获地址详情
    public function Findaddres($id){
        $data=db('addres')->find($id);
        if($data){
            $this->return_msg(200,"查询成功",$data);
        }else{
            $this->return_msg(400,"查询失败");
        }
    }


    //用户地址的修改

    public function Editaddres($uid,$token,$id,$consignee,$addres,$phone,$check){
        if($this->checktoken($token)){
            $date['consignee']=$consignee;
            $date['addres']=$addres;
            $date['phone']=$phone;
            $date['check']=$check;
            $date['check'] ? $date["check"] = 1 : $date["check"] = 0;
            //如果没有收货地址，新增用户收货地址
            $res=db('addres')->where(['uid'=>$uid])->select();
            if($date['check']==1){
                foreach($res as $k=> $v){
                    db('addres')->where(['id'=>$v['id']])->update(['check'=>0]);
                }
                db('addres')->where(['id'=>$id])->update($date);
                $this->return_msg(200,"添加成功");
            }else{
                db('addres')->where(['id'=>$id])->update($date);
                $this->return_msg(200,"添加成功");
            }

//            db('addres')->where(['id'=>$id])->update($date);
//            $this->return_msg(200,"修改成功");

        }else{
            $this->return_msg(400,"token已过期");
        }
    }


    //用户收获地址的删除
    public function Deladdres($uid,$token,$aid){
        if($this->checktoken($token)){
            db('addres')->where(['id'=>$aid,'uid'=>$uid])->delete();
            $this->return_msg(200,"删除成功");

        }else{
            $this->return_msg(400,"token已过期");
        }
    }



    //获取用户的默认收货地址
    public function FindsAddres($uid){
        //如果有默认收货地址
        $res=db('addres')->where(['uid'=>$uid,'check'=>1])->find();
        if($res){
            $this->return_msg(200,"查询成功",$res);
        }else{  //没有设置默认收货地址，那么就给找第一个收货地址
            $res=db('addres')->find($uid);
            if($res){
                $this->return_msg(200,"查询成功",$res);
            }else{
                $this->return_msg(400,"该用户未设置收货地址",$res);
            }
            }
        }

    //获取购物车的里面的商品
    public  function FindGoods($SelectGoods,$uid){
        $SelectGood=explode(',',$SelectGoods);
        $Goods=array();
        foreach($SelectGood as $V){
            $res=model('goods')->field('id,goods_name,og_thumb,shop_price')->where(['id'=>$V])->find();
            //查找该用户购物车该商品的数量
            $res=$res->toArray();
            $num=db('cart')->field('num')->where(['uid'=>$uid,'goodsid'=>$V])->find();
           // $num=$num['num']->toArray();
            $res=array_merge($res,$num);

            $Goods[]=$res;
        }
        if(count($Goods)>0){
            $this->return_msg(200,"查询成功",$Goods);
        }else{
            $this->return_msg(400,"查询失败",$Goods);
        }

    }





    //获取用户信息
    public  function UserInfo($code,$openid,$nickName,$avatarUrl,$province){

        if($this->CheckOpendid($code,$openid)){//验证openid

            $user=db('user')->where('openid','=',$openid)->find();
            if($user){
                $res=$this->restToken($openid,$nickName,$avatarUrl,$province);
                if($res){
                    $this->return_msg(200,"登陆成功",$user);
                }else{
                    $this->return_msg(401,"登陆失败，tonken重置失败");
                }
            }else{
                $this->return_msg(400,"登陆失败，用户不存在，请先注册");
            }

        }else{
            $this->return_msg(401,"登陆失败，openid不正确");
        }

    }




    //注册用户
    public function Register($openid){

        $reg_time=date(time());
        $tonken_time=date(time());
        $tonken=$this->createNoncestr(32);
        $openid=$openid;
        $data=[
            'reg_time'=>$reg_time,
            'tonken_time'=>$tonken_time,
            'tonken'=>$tonken,
            'openid'=>$openid
        ];
        $res=db('user')->insert($data);
        if($res){
            $this->return_msg(200,"注册成功",$data);
        }else{
            $this->return_msg(400,"注册失败");
        }
    }







}