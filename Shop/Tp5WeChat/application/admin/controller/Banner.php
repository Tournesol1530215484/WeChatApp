<?php
namespace app\admin\controller;
use think\Controller;
class Banner extends Base{

     public function BannerList(){
         $Banner=db('banner')->paginate(5);
         $this->assign('Banner',$Banner);
         return view('BannerList');
     }


    public function BannerAdd(){
        if(request()->isPost()){
           $data=input('post.');

           //处理连接
            if(stripos($data['link'],'http://')===false){
                $data['link']='http://'.$data['article_url'];//超链接后缀名
            }
            // 处理图片
            if($_FILES['images']['tmp_name']){
                $file = request()->file('images');
                $info = $file->move(ROOT_PATH . 'public' .DS.'static'.DS.'uploads');
                if($info){
                    $imgpath=date("Ymd").'/'.$info->getFilename();
                    $data['images']=$imgpath;
                }else{
                    $file->getError();
                    die;
                }

            }
            $res=db('banner')->insert($data);
            if($res){
//                echo "<script>alert('您不在本次活动范围内哦')</script>";
//                header("refresh:0;url='BannerAdd'");
                echo "<script>alert('提交成功');window.location.href='BannerList';</script>";

            }else{
                echo "<script>alert('添加失败');window.location.href='BannerAdd';</script>";
            }
        }
        return view('BannerAdd');
    }

    public function BannerEdit(){
        if(request()->isPost()){
            $data=input('post.');
            if($_FILES['images']['tmp_name']){
                $filename=db('banner')->field('images')->find($data['id']);
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
                    $data['images']=$imgpath;
                }else{
                    $file->getError();
                    die;
                }
            }

            $res=db('banner')->update($data);
            if($res!==false){
                echo "<script>alert('修改成功');window.location.href='BannerList';</script>";
            }else{
                echo "<script>alert('修改失败');window.location.href='BannerEdit';</script>";
            }
        }
        $id=input('id');
        $banner=db('banner')->find($id);
        $this->assign('banner',$banner);
        return view('BannerEdit');
    }

    public function BannerDel(){
        $id=input('id');
        $filename=db('banner')->field('images')->find( $id);
        if(isset($filename)){
            $img=$filename['images'];
            $file=PICTURE.$img;
            @unlink($file);
        }
        $res=db('banner')->delete($id);
        if($res){
            echo "<script>alert('删除成功');window.history.go(-1); </script>";

        }else{
//            header("refresh:0;url='BannerList'");
            echo "<script>alert('删除失败');window.history.go(-1);</script>";
        }

    }



}