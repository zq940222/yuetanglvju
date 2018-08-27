<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 9:18
 */

namespace app\api\service;

use think\Loader;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class WxRefund
{
    public function wxRefund($totalprice, $orderNo)
    {
        $merchid = \WxPayConfig::MCHID;

        $input = new WxPayRefund();
        $input->SetOut_trade_no($orderNo);			//自己的订单号
//        $input->SetTransaction_id($order['transaction_id']);  	//微信官方生成的订单流水号，在支付成功中有返回
        $input->SetOut_refund_no(getrand_num(true));			//退款单号
        $input->SetTotal_fee($totalprice);			//订单标价金额，单位为分
        $input->SetRefund_fee($totalprice);			//退款总金额，订单总金额，单位为分，只能为整数
        $input->SetOp_user_id($merchid);

        $result = \WxPayApi::refund($input);	//退款操作

        return $result;

    }
}