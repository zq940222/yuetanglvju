<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/25
 * Time: 16:58
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\AliPay as AliPayService;
use app\api\validate\IDMustBePostiveInt;

class AliPay extends BaseController
{
    public function getHotelPreOrder($id = '')
    {
        (new IDMustBePostiveInt())->goCheck();
        $pay = new AliPayService($id);
        $orderString = $pay->hotelPay();
        return ['orderString' => $orderString];
    }

    public function getProductPreOrder($id = '')
    {
        (new IDMustBePostiveInt())->goCheck();
        $pay = new AliPayService($id);
        $orderString = $pay->productPay();
        return ['orderString' => $orderString];
    }

    public function getRechargePreOrder($id = '')
    {
        (new IDMustBePostiveInt())->goCheck();
        $pay = new AliPayService($id);
        $orderString = $pay->recharge();
        return ['orderString' => $orderString];
    }

}