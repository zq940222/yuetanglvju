<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/6
 * Time: 18:44
 */

namespace app\admin\model;


use app\admin\controller\Upload;
use traits\model\SoftDelete;

class Image extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    protected $visible = ['id','url'];

    public function getUrlAttr($value,$data)
    {
        return $this->prefixImgUrl($value,$data);
    }

//    public function setUrlAttr($value)
//    {
//        if ($value)
//        {
//            Upload::move($value);
//        }
//        return $value;
//    }

}