<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/9
 * Time: 17:30
 */

namespace app\admin\model;


use traits\model\SoftDelete;

class Coupon extends BaseModel
{
    use SoftDelete;

    public function getStimeAttr($value)
    {
        return date('Y-m-d',$value);
    }

    public function getEtimeAttr($value)
    {
        return date('Y-m-d',$value);
    }
}