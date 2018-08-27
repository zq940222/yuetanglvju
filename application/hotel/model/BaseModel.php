<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 19:06
 */

namespace app\hotel\model;


use think\Model;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = true;

    public function getCreateTimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }

    public function getUpdateTimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }

    protected function prefixImgUrl($value,$data)
    {
        $finalUrl = $value;
        if ($data['from'] == 1){
            $finalUrl = config('setting.img_prefix').$value;
        }
        return $finalUrl;
    }
}