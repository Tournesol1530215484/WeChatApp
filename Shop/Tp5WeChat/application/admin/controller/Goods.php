<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/25
 * Time: 17:42
 */
namespace app\admin\controller;
use Catetree\Catetree;
use app\admin\model\Goods as GoodsModel;
use app\admin\model\Goodsphoto as GoodsphotoModel;
use think\Controller;
use think\Db;

class Goods extends  Base{
    //==========================商品列表==========================
    public function GoodsList(){
        $Goods=Db::table('wx_goods')
            ->alias('g')
            ->field("g.*,c.cate_name,c.images")
            ->join('wx_cate c','c.id=g.category_id','left')
            ->paginate(10);
        $this->assign([
            'Goods'=>$Goods,
        ]);

        return view('GoodsList');
    }

    //==========================商品添加==========================

    public function GoodsAdd(){
        if(request()->isPost()){
            $data=input('post.');
            //对所属专题进行增加
            if(isset($data['goods_rec_id'])){
                $specialid=$data['goods_rec_id'];
            }
            $files = request()->file('og_photo');
            if(!empty($files)){
                foreach($files as $file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public' .DS.'static'.DS.'uploads');
                    if($info){
                        $imgpath=date("Ymd").'/'.$info->getFilename();
                        $og_photo[]=$imgpath;       //把文件单独提取出来，进行存储
                    }else{
                        $file->getError();
                        die;
                    }
                }
            }
            $GoodsModel = new GoodsModel();
            //上传图片
            if($_FILES['og_thumb']['tmp_name']){
                $file = request()->file('og_thumb');
                $info = $file->move(ROOT_PATH . 'public' .DS.'static'.DS.'uploads');
                if($info){
                    $imgpath=date("Ymd").'/'.$info->getFilename();
                    $data['og_thumb']=$imgpath;     //上传本表文件数据
                }else{
                    $file->getError();
                    die;
                }
            }


            //上传图片
            if($_FILES['big_thumb']['tmp_name']){
                $file = request()->file('big_thumb');
                $info = $file->move(ROOT_PATH . 'public' .DS.'static'.DS.'uploads');
                if($info){
                    $imgpath=date("Ymd").'/'.$info->getFilename();
                    $data['big_thumb']=$imgpath;     //上传本表文件数据
                }else{
                    $file->getError();
                    die;
                }
            }

            //生成商品编号
            $data['goods_code']=time().rand(111111,999999);

            $res = $GoodsModel->allowField(true)->save($data);//添加商品的基本信息
            $goodsid=$GoodsModel->getLastInsID();       //获取自增id
            if($specialid){
                foreach($specialid as $v){
                    Db::name('special_res')->data(['goodsid'=>$goodsid,'specialid'=>$v])->insert();
                }
            }

            if($res && $goodsid){
                foreach($og_photo as $k=>$v){
                    Db::name('goodsphoto')->data(['goodsid'=>$goodsid,'og_photo'=>$v])->insert();

                }

                echo "<script>alert('提交成功');window.location.href='GoodsList';</script>";
            }else{
                echo "<script>alert('添加失败');window.location.href='GoodsAdd';</script>";
            }
        }
        //获取所有栏目信息
        $cate = new Catetree();
        $date = db('cate')->select();
        $Cate = $cate->ChildTree($date);
        //获取所属专题
        $Rec=db('special')->select();
        $this->assign([
            'Cate'=>$Cate,
            'Rec'=>$Rec
        ]);
        return view('Goods/GoodsAdd');

    }

    //===================商品修改==========================
    public function GoodsEdit(){
        if(request()->isPost()){
            $date=input('post.');
            //对所属专题进行增加
            if(isset($data['goods_rec_id'])){
                $specialid=$data['goods_rec_id'];
            }
            //修改商品相册
            $files = request()->file('og_photo');
//            $og_photo='';
            if(!empty($files)){
                foreach($files as $file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public' .DS.'static'.DS.'uploads');
                    if($info){
                        $imgpath=date("Ymd").'/'.$info->getFilename();
                        $og_photo[]=$imgpath;       //把文件单独提取出来，进行存储
                    }else{
                        $file->getError();
                        die;
                    }
                }
            }
            //修改商品的基本信息
            if($_FILES['og_thumb']['tmp_name']){
                $filename=db('goods')->field('og_thumb')->find($date['id']);
                if(isset($filename)){
                    $img=$filename['og_thumb'];
                    $file=PICTURE.$img;
                    @unlink($file);
                }
                //图片重新上传
                $file = request()->file('og_thumb');
                $info = $file->move(ROOT_PATH . 'public' .DS.'static'.DS.'uploads');
                if($info){
                    $imgpath=date("Ymd").'/'.$info->getFilename();
                    $date['og_thumb']=$imgpath;
                }else{
                    $file->getError();
                    die;
                }
            }
            if($_FILES['big_thumb']['tmp_name']){
                $filename=db('goods')->field('big_thumb')->find($date['id']);
                if(isset($filename)){
                    $img=$filename['big_thumb'];
                    $file=PICTURE.$img;
                    @unlink($file);
                }
                //图片重新上传
                $file = request()->file('big_thumb');
                $info = $file->move(ROOT_PATH . 'public' .DS.'static'.DS.'uploads');
                if($info){
                    $imgpath=date("Ymd").'/'.$info->getFilename();
                    $date['big_thumb']=$imgpath;
                }else{
                    $file->getError();
                    die;
                }
            }

            $GoodsModel=new GoodsModel();

            //删除所有的专题,在进行添加
            db('special_res')->where('goodsid','=',$date['id'])->delete();
            if(isset($specialid)){
                foreach($specialid as $v){
                    Db::name('special_res')->data(['goodsid'=>$date['id'],'specialid'=>$v])->insert();
                }

            }
            unset($date['goods_rec_id']);
            $res = $GoodsModel->update($date);//添加商品的基本信息

            if($res){
                if(isset($og_photo)){
                    foreach($og_photo as $k=>$v){
                        Db::name('goodsphoto')->data(['goodsid'=>$date['id'],'og_photo'=>$v])->insert();
                        //拼接插入数据库信息
                    }
                }


                echo "<script>alert('提交成功');window.location.href='GoodsList';</script>";
            }else{
                echo "<script>alert('添加失败');window.location.href='GoodsAdd';</script>";
            }
        }
        $id=input('id');
        $good=db('goods')->find($id);
        //获取所属的专题
        $specialid=db('special_res')->where('goodsid','=',$id)->field('specialid')->select();
        if($specialid){
            foreach($specialid as $v){
                $sid[]=$v['specialid'];
            }
        }else{
            $sid[]='';
        }
       // $good['sid']=implode(',',$sid);
        $good['sid']=$sid;

        //获取所有栏目信息
        $cate = new Catetree();
        $date = db('cate')->select();
        $Cate = $cate->ChildTree($date);
        //获取所有商品的详情图
        $Img=db('goodsphoto')->where('goodsid','=',$id)->select();

        //获取所属专题
        $Rec=db('special')->select();

        $this->assign([
            'Cate'=>$Cate,
            'good'=>$good,
            'Img'=>$Img,
            'Rec'=>$Rec
        ]);
        return view('Goods/GoodsEdit');


        }

    //删除图片
    public function  DelGoodsImg(){
        $id=input('id');
        $filename=db('goodsphoto')->where('id','=',$id)->find();
        if(isset($filename['og_photo'])){
            $file=PICTURE.$filename['og_photo'];
            @unlink($file);
        }

        if(isset($filename['big_thumb'])){
            $file=PICTURE.$filename['big_thumb'];
            @unlink($file);
        }


        $res=db('goodsphoto')->delete($id);
        if($res){
            return (['result'=>1,'msg'=>'删除成功']);
        }else{
            return (['result'=>0,'msg'=>'删除失败']);
        }


    }

    //删除商品
    public function  GoodsDel(){
        $id=input('id');
        $pho=db('goods')->where('id','=',$id)->find();
           if(isset($pho)){
               $file=PICTURE.$pho['og_thumb'];
               @unlink($file);
           }

        $res=db('goods')->delete($id);
        //$res=1;
        if($res){
            //删除对应专题
            $Special=db('special_res')->where('goodsid','=',$id)->select();
            if(!empty($Special)){
                foreach($Special as $k=>$V){
                    //删除数据库special
                    db('special_res')->where('goodsid',$V['goodsid'])->delete();
                }
            }


            //删除图片
           $photos=db('goodsphoto')->where('goodsid','=',$id)->select();
           if(!empty($photos)){
               foreach($photos as $v){
                   $file=PICTURE.$v['og_photo'];
                   @unlink($file);
               }
               $result=db('goodsphoto')->where('goodsid','=',$id)->delete();
           }

            if($res){
                echo "<script>alert('删除成功');window.history.go(-1);</script>";
            }else{
                echo "<script>alert('删除失败');window.history.go(-1);</script>";
            }
        }else{
            echo "<script>alert('删除失败');window.history.go(-1);</script>";
        }
    }

}