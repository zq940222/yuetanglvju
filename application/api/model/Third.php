<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/25
 * Time: 17:53
 */

namespace app\api\model;


class Third extends BaseModel
{
    public static function getByOpenID($openid,$platform){
        $thirdData = self::where('openid','=',$openid)
            ->where('platform','=',$platform)
            ->where('status','=',1)
            ->find();
        return $thirdData;
    }
}