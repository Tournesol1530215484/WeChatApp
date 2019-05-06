<?php
namespace app\admin\controller;
use think\Controller;
class Special extends Controller{

    public  function SpecialList(){
        $Special=db('special')->paginate(10);
        $this->assign('Special',$Special);
       return view('SpecialList');
    }

    public function SpecialAdd(){
        if(request()->isPost()){
            $data=input('post.');
            //处理图片
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
            $res=db('special')->insert($data);
            if($res){
//                echo "<script>alert('您不在本次活动范围内哦')</script>";
//                header("refresh:0;url='BannerAdd'");
                echo "<script>alert('提交成功');window.location.href='SpecialList';</script>";

            }else{
                echo "<script>alert('添加失败');window.location.href='SpecialAdd';</script>";
            }

        }
        return view('SpecialAdd');
    }


    public function SpecialEdit(){
        if(request()->isPost()){
            $data=input('post.');
            if($_FILES['images']['tmp_name']){
                $filename=db('special')->field('images')->find($data['id']);
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

            $res=db('special')->update($data);
            if($res!==false){
                echo "<script>alert('修改成功');window.location.href='SpecialList';</script>";
            }else{
                echo "<script>alert('修改失败');window.location.href='SpecialEdit';</script>";
            }
        }
        $id=input('id');
        $Special=db('special')->find($id);
        $this->assign('Special',$Special);
        return view('SpecialEdit');
    }

    public function SpecialDel(){
        $id=input('id');

        $filename=db('special')->field('images')->find( $id);
        if(isset($filename)){
            $img=$filename['images'];
            $file=PICTURE.$img;
            @unlink($file);
        }
        $res=db('special')->delete($id);
        db('special_res')->where('specialid','=',$id)->delete($id);
        if($res){
            echo "<script>alert('删除成功');window.history.go(-1); </script>";

        }else{
//            header("refresh:0;url='BannerList'");
            echo "<script>alert('删除失败');window.history.go(-1);</script>";
        }
    }

}