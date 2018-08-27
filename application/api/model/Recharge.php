<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/28
 * Time: 19:01
 */

namespace app\api\model;


class Recharge extends BaseModel
{
    public static function recharge($uid, $money)
    {
        $orderNo = self::makeOrderNo();
        $model = self::create([
            'order_no' => $orderNo,
            'total_price' => $money,
            'user_id' => $uid
        ]);
        $orderID = $model->id;
        return [
            'order_id' => $orderID,
            'order_no' => $orderNo
        ];
    }

    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N');
        $orderSn =
            $yCode[intval(date('Y')) - 2018] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(1000, 9999));
        return $orderSn;
    }
}