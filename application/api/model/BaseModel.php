<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/8
 * Time: 17:54
 */



namespace app\api\model;


use think\Model;
use traits\model\SoftDelete;

class BaseModel extends Model
{
    use SoftDelete;

    protected $autoWriteTimestamp = true;

    protected $deleteTime = 'delete_time';

    protected $hidden = ['delete_time','update_time'];

    protected function prefixImgUrl($value,$data)
    {
        $finalUrl = $value;
        if ($data['from'] == 1){
            $finalUrl = config('setting.img_prefix').$value;
        }
        return $finalUrl;
    }
}