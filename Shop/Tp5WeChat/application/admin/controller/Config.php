<?php
namespace app\admin\controller;
use think\Controller;
class Config extends Controller{

     public function ConfigList(){
         return view('ConfigList');
     }


    public function ConfigAdd(){
        return view('ConfigAdd');
    }

    public function ConfigEdit(){
        return view('ConfigAdd');
    }
    public function ConfigDel(){
        return view('ConfigAdd');
    }


    //配置项的值

    public function ConfiguresList(){
        return view('ConfiguresList');
    }


    public function ConfiguresAdd(){

        if(request()->isPost()){
            $data=input('post.');
            if(isset($data['values'])){
                $data['values']=preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)|(\.)/",',',$data['values']);
            }
            $res=db('config')->insert($data);
           if($res){
               $this->success('数据更新成功！');
                 $this->success('添加成功',url('ConfiguresList'));
               return view('ConfiguresAdd');
            }else{
                $this->error('删除文章失败');
            }

        }
        return view('ConfiguresAdd');
    }

    public function ConfiguresEdit(){
        return view('ConfigAdd');
    }
    public function ConfiguresDel(){
        return view('ConfigAdd');
    }

}