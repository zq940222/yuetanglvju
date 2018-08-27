<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/27
 * Time: 9:53
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\AliPayNotify;
use app\api\service\WxNotify;

class Notify extends BaseController
{
    public function hotelAliNotify()
{
    $notify = new AliPayNotify();
    $type = 'hotel';
    return $notify->notify($type);
}

    public function productAliNotify()
    {
        $notify = new AliPayNotify();
        $type = 'product';
        $notify->notify($type);
    }

    public function rechargeAliNotify()
    {
        $notify = new AliPayNotify();
        $type = 'recharge';
        $notify->notify($type);
    }

    public function receiveNotify()
    {
        $notify = new WxNotify();
        $notify->Handle();
    }



}