<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 20:38
 */

namespace app\hotel\model;


use app\hotel\controller\Upload;
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

    public function setUrlAttr($value)
    {
        Upload::move($value);
        return $value;
    }
}