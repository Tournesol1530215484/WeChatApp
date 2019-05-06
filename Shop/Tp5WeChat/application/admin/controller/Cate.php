<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/12
 * Time: 14:40
 */
namespace app\admin\controller;
use Catetree\Catetree;
use app\admin\controller\Article;
class Cate extends Base{
    public function CateList(){
        $cate=new Catetree();
        $date=db('cate')->select();
        $Cate=$cate->ChildTree($date);
        $this->assign('Cate',$Cate);
        return view('Cate/CateList');
    }

    public function CateAdd(){
        if(request()->isPost()){
            $date=input('post.');
            $date['cate_type']=$date['pid'];
            // 处理图片
            if($_FILES['images']['tmp_name']){
                $file = request()->file('images');
                $info = $file->move(ROOT_PATH . 'public' .DS.'static'.DS.'uploads');
                if($info){
                    $imgpath=date("Ymd").'/'.$info->getFilename();
                    $date['images']=$imgpath;
                }else{
                    $file->getError();
                    die;
                }

            }

            $res=db('cate')->insert($date);
            if($res){
                echo "<script>alert('提交成功');window.location.href='CateList';</script>";

            }else{
                echo "<script>alert('添加失败');window.location.href='CateAdd';</script>";
            }
        }
        //获取推荐分类
        $cate=new Catetree();
        $date=db('cate')->select();
        $Cate=$cate->ChildTree($date);
        $this->assign('Cate',$Cate);
        return view('Cate/CateAdd');
    }

    public  function CateEdit(){
        if(request()->isPost()){
            $date=input('post.');
            if($date['pid']==$date['id']){
                echo "<script>alert('修改失败,不可以成为自己的子栏目');window.location.href='CateList';</script>";
            }
            $date['cate_type']=$date['pid'];
            $date=input('post.');
            if($_FILES['images']['tmp_name']){
                $filename=db('cate')->field('images')->find($date['id']);
                if(isset($filename)){
                    $img=$filename['images'];
                    $file=PICTURE.$img;
                    @unlink($file);
                }
                //图片重新上传
                $file = request()->file('images');
                $info = $file->move(ROOT_PATH . 'public' .DS.'static'.DS.'uploads');
                if($info){
                    $imgpath=date("Ymd").'/'.$info->getFilename();
                    $date['images']=$imgpath;
                }else{
                    $file->getError();
                    die;
                }
            }

            $res=db('cate')->update($date);
            if($res!==false){
                echo "<script>alert('修改成功');window.location.href='BannerList';</script>";
            }else{
                echo "<script>alert('修改失败');window.history.go(-1);</script>";
            }

        }
        $id=input('id');
        $Catecontent=db('cate')->find($id);
        $cate=new Catetree();
        $date=db('cate')->field('id,cate_name,pid')->select();
        $Cate=$cate->ChildTree($date);
        $this->assign([
            'Cate'=>$Cate,
            'Catecontent'=>$Catecontent,
        ]);
        return view('Cate/CateEdit');
    }


    public  function CateDel(){
        $id=input('id');
        $catetree=new Catetree();//实例化无限极分类方法
        $cateres=db('cate')->field('id,pid')->select();//根据id删除自己
        $cateres=$catetree->Parenttree($id,$cateres);//无限极分类（见上一篇）
        $cateres[]=$id;
//        foreach($cateres as $value){
//            //value是栏目
//            $artres=db('article')->field('id,pid')->select();
//               foreach($artres as $k=>$v){
//                   //$v文章
//                   if($value==$v['pid']){
//                       //对文章里面的文件进行删除
//                       $filename=db('article')->field('article_logo')->find($v['id']);
//                       if(isset($filename)){
//                           $img=$filename['article_logo'];
//                           $file=PICTURE.$img;
//                           @unlink($file);
//                       }
//                       //根据id删除文章
//                       $delarticle=db('article')->delete($v['id']);
//                       if(!$delarticle){
//                           $this->error('删除文章异常');
//                           die;
//                       }
//                   }
//               }
//            //删除栏目缩略图
//            $filename=db('banner')->field('images')->find( $value);
//            if(isset($filename)){
//                $img=$filename['images'];
//                $file=PICTURE.$img;
//                @unlink($file);
//            }
//            $res=db('cate')->where('id',$value)->delete();
//        }
        //删除栏目缩略图

        foreach($cateres as $value){
            $filename=db('cate')->field('images')->find($value);
            if(isset($filename)){
                $img=$filename['images'];
                $file=PICTURE.$img;
                @unlink($file);
            }
            $res=db('cate')->where('id',$value)->delete();
        }
        if($res){
            echo "<script>alert('删除成功');window.history.go(-1); </script>";

        }else{
//            header("refresh:0;url='BannerList'");
            echo "<script>alert('删除失败');window.history.go(-1);</script>";
        }
    }

    public function Catesort(){
        $ids=input('ids');
        $sort=input('sort');
        //$cateres=db('cate')->field('id,sort')->select();
        $obj=db('cate');
        $result = $obj->where(array('id' => $ids))->setField('sort',$sort);
        return $ids;

       // $res=$this->Sort($ids,$sort,$cateres);

    }


}