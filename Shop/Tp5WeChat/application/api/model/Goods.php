<?php
namespace app\api\Model;
use think\Model;
class Goods extends Model{

    public function getOgThumbAttr($value)
    {
        $status = config('queue.base_url').$value;
        return $status;
    }

    public function getBigThumbAttr($value)
    {
        $status = config('queue.base_url').$value;
        return $status;








    }
}







