<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/20
 * Time: 17:33
 */

namespace app\api\controller\v1;


use app\api\validate\IDMustBePostiveInt;

class BalancePay
{
    public function getPreOrder($id = '')
    {
        (new IDMustBePostiveInt())->goCheck();

        $WxPay = new WxPayService();
        return $WxPay->pay();
    }
}