<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/3
 * Time: 19:27
 */

namespace app\api\service;

use app\api\model\HotelAppointment as HotelAppointmentModel;
use app\api\model\Order;

class Refund
{
    public function refundList($uid)
    {
        $hotelList = $this->hotelRefundList($uid);
        $productList = $this->productRefundList($uid);
        $lists = [];
        foreach ($hotelList as $value)
        {
            $lists[] = [
                'name' => $value['snap_hotel_name'],
                'detail' => [
                    ['image' => $value['snap_hotel_img'],
                    'name' => $value['room_type'],
                    'num' => $value['room_count']]
                ],
                'price' => $value['total_price'],
                'create_time' => $value['create_time']
            ];
        }
        foreach ($productList as $value)
        {
            $detail = [];
            foreach ($value->product as $v)
            {
                $detail[] = [
                    'image' => $v['product_image'],
                    'name' => $v['product_name'],
                    'num' => $v['product_num']
                ];
            }
            $lists[] = [
                'name' => '商城',
                'detail' => $detail,
                'price' => $value['order_total_price'],
                'create_time' => $value['create_time']
            ];
        }
        array_multisort($lists);
        return $lists;
    }

    private function productRefundList($uid)
    {
        $res = Order::with(['product'])
            ->where('user_id','=',$uid)
            ->where('status','in',[6,7,8])
            ->select();
        return $res;
    }

    private function hotelRefundList($uid)
    {
        $res = HotelAppointmentModel::where('user_id','=',$uid)
            ->where('status','in',[7,8,9])
            ->select();
        return $res;
    }
}