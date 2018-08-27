<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/16
 * Time: 14:49
 */

namespace app\hotel\model;


use traits\model\SoftDelete;

class User extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getGenderAttr($value)
    {
        $arr = [
            '未知',
            '男',
            '女'
        ];
        return $arr[$value];
    }

    public function image()
    {
        return $this->belongsTo('Image','img_id','id');
    }
}