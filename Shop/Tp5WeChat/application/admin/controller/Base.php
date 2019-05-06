<?php
namespace app\admin\controller;
use think\Controller;
class Base extends Controller{


    //页面提示跳转
//    function message($msgTitle,$message,$jumpUrl){
//        $str = '<!DOCTYPE HTML>';
//        $str .= '<html>';
//        $str .= '<head>';
//        $str .= '<meta charset="utf-8">';
//        $str .= '<title>页面提示</title>';
//        $str .= '<style type="text/css">';
//        $str .= '*{margin:0; padding:0}a{color:#369; text-decoration:none;}a:hover{text-decoration:underline}body{height:100%; font:12px/18px Tahoma, Arial,  sans-serif; color:#424242; background:#fff}.message{width:450px; height:120px; margin:16% auto; border:1px solid #99b1c4; background:#ecf7fb}.message h3{height:28px; line-height:28px; background:#2c91c6; text-align:center; color:#fff; font-size:14px}.msg_txt{padding:10px; margin-top:8px}.msg_txt h4{line-height:26px; font-size:14px}.msg_txt h4.red{color:#f30}.msg_txt p{line-height:22px}';
//        $str .= '</style>';
//        $str .= '</head>';
//        $str .= '<body>';
//        $str .= '<div class="message">';
//        $str .= '<h3>'.$msgTitle.'</h3>';
//        $str .= '<div class="msg_txt">';
//        $str .= '<h4 class="red">'.$message.'</h4>';
//        $str .= '<p>系统将在 <span style="color:blue;font-weight:bold">3</span> 秒后自动跳转,如果不想等待,直接点击 <a href="{$jumpUrl}">这里</a> 跳转</p>';
//        $str .= "<script>setTimeout('location.replace('".$jumpUrl."')',2000)</script>";
//        $str .= '</div>';
//        $str .= '</div>';
//        $str .= '</body>';
//        $str .= '</html>';
//        echo $str;
//    }


    /**
     * 去除多维数组中的空值
     * @author
     * @return mixed
     * @param $arr 目标数组
     * @param array $values 去除的值  默认 去除  '',null,false,0,'0',[]
     */
    function filter_array($arr, $values = ['', null, false, 0, '0',[]]) {
        foreach ($arr as $k => $v) {
            if (is_array($v) && count($v)>0) {
                $arr[$k] = filter_array($v, $values);
            }
            foreach ($values as $value) {
                if ($v === $value) {
                    unset($arr[$k]);
                    break;
                }
            }
        }
        return $arr;
    }

}