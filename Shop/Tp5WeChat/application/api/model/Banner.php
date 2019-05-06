<?php
namespace app\api\Model;
use think\Model;
class Banner extends Model{

    public function getImagesAttr($value)
    {
        $status = config('queue.base_url').$value;
        return $status;
    }
}