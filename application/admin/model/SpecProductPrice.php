<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/12
 * Time: 17:01
 */

namespace app\admin\model;


use app\admin\controller\Upload;

class SpecProductPrice extends BaseModel
{
    public function getImageAttr($value)
    {
        return config('setting.img_prefix').$value;
    }

//    public function setImageAttr($value)
//    {
//        Upload::move($value);
//        return $value;
//    }
}