<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 16:13
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\HotelAppointment;
use app\api\model\UserCoupon;
use app\lib\enum\HotelAppointmentStatusEnum;

class Crontab extends BaseController
{
    public function couponOvertime()
    {
        $couponModel = new UserCoupon();
        $res = $couponModel->where('status','=',1)
            ->where('end_time','<',date('Y-m-d',time()))
            ->update(['status'=> 2]);
        return $res;
    }

    public function orderOvertime()
    {
        $orderModel = new HotelAppointment();
        $res = $orderModel->where('status','=',HotelAppointmentStatusEnum::UnPay)
            ->where('check_in_time','<',date('Y-m-d',time()))
            ->update(['status'=>HotelAppointmentStatusEnum::Canceled]);
        return $res;
    }
}