<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/7
 * Time: 10:53
 */

namespace app\api\model;


class Expressage extends BaseModel
{
    protected $visible = ['express_fee'];
    public static function getExpressage($city = '')
    {
        $expressage = self::where('ship_city','like',"%$city%")
            ->find();
        if (!$expressage) {
            $expressage = self::where('is_default','=',1)
                ->find();
        }
        return $expressage;
    }
}