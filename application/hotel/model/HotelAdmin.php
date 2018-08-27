<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 19:06
 */

namespace app\hotel\model;


class HotelAdmin extends BaseModel
{
    public function setPasswordAttr($value)
    {
        return md5($value);
    }

    public static function checkLogin($username, $password)
    {
        $password=self::setPassword($password);

        return self::field(['id', 'account','hotel_id'])->where(['account'=>$username, 'password'=>$password])->find();
    }


    private static function setPassword($password)
    {
        return md5($password);
    }

}