<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/9
 * Time: 11:31
 */

namespace app\admin\model;


class HotelAdmin extends BaseModel
{
    public function setPasswordAttr($value)
    {
        return md5($value);
    }
}