<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/6
 * Time: 8:57
 */

namespace app\admin\model;


class Admin extends BaseModel
{
    public function setPasswordAttr($value)
    {
        return md5($value);
    }

    public static function checkLogin($username, $password)
    {
        $password=self::setPassword($password);

        return self::field(['id', 'username'])->where(['username'=>$username, 'password'=>$password])->find();
    }


    private static function setPassword($password)
    {
        return md5($password);
    }

    public function authGroup()
    {
        return $this->belongsToMany('AuthGroup','AuthGroupAccess','group_id','uid');
    }
}