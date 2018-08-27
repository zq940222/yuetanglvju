<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/3
 * Time: 16:05
 */

namespace app\api\controller\v1;


use app\api\validate\IDMustBePostiveInt;
use app\api\service\WxPay as WxPayService;

class WxPay
{
    public function getHotelPreOrder($id = '')
    {
        (new IDMustBePostiveInt())->goCheck();
        $pay = new WxPayService($id);
        $orderString = $pay->hotelPay();
        return json(['orderString' => $orderString]);
    }

    public function getProductPreOrder($id = '')
    {
        (new IDMustBePostiveInt())->goCheck();
        $pay = new WxPayService($id);
        $orderString = $pay->productPay();
        return json(['orderString' => $orderString]);
    }

    public function getRechargePreOrder($id = '')
    {
        (new IDMustBePostiveInt())->goCheck();
        $pay = new WxPayService($id);
        $orderString = $pay->recharge();
        return ['orderString' => $orderString];
    }
}