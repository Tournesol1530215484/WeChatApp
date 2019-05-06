<?php
namespace app\api\Model;
use think\Model;
class Goodsphoto extends Model{

    public function getOgPhotoAttr($value)
    {
        $status = config('queue.base_url').$value;
        return $status;
    }
}