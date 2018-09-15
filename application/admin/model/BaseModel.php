<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/6
 * Time: 9:20
 */

namespace app\admin\model;


use think\Model;
use traits\model\SoftDelete;

class BaseModel extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

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