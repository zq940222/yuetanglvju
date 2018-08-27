<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/15
 * Time: 10:47
 */

namespace app\api\service;

use app\api\model\HotelRoomType;
use app\api\model\UserCoupon;
use app\api\model\HotelAppointment as HotelAppointmentModel;
use app\lib\exception\OrderException;

class HotelAppointment
{
    protected $uid;

    protected $oRoom;

    protected $room;

    public function place($uid, $dataArray)
    {
        $this->uid = $uid;
        $this->oRoom = $dataArray;
        $this->room = $this->getRoomByOrder($dataArray);

        $status = $this->getOrderStatus();
        if (!$status['pass']){
            $status['order_id'] = -1;
            return $status;
        }
        //开始创建订单
        $orderSnap = $this->snapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;
    }

    private function createOrder($snap)
    {
        $orderNo = self::makeOrderNo();
        $order = new HotelAppointmentModel();
        $order->user_id = $this->uid;
        $order->order_no = $orderNo;
        $order->total_price = $snap['orderPrice'];
        $order->room_count = $snap['roomCount'];
        $order->snap_hotel_img = $snap['snapHotelImg'];
        $order->snap_hotel_name = $snap['snapHotelName'];
        $order->room_type = $snap['roomType'];
        $order->hotel_id = $this->room['hotel']['id'];
        $order->hotel_room_id = $this->oRoom['hotel_room_id'];
        $order->check_in_time = $this->oRoom['check_in_time'];
        $order->check_out_time = $this->oRoom['check_out_time'];
        $order->room_real_price = $this->oRoom['type'] == 1?$this->room['price']:$this->room['group_price'];
        $order->room_original_price = $this->room['price'];
        $order->latest_time = $this->oRoom['latest_time'];
        $order->occupant_name = $this->oRoom['occupant_name'];
        $order->mobile = $this->oRoom['mobile'];
        $order->coupon_id = $this->oRoom['coupon_id'];
        $order->coupon_price = $this->getCouponPrice($this->oRoom['coupon_id']);
        $order->invoice = $this->oRoom['invoice'];
        $order->type = $this->oRoom['type'];
        $order->group_list_id = $this->oRoom['group_list_id'];

        $order->save();

        $orderID = $order->id;
        $create_time = $order->create_time;

        return [
            'order_no' => $orderNo,
            'order_id' => $orderID,
            'create_time' => $create_time
        ];
    }

    //生成订单快照
    private function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'snapHotelName' => '',
            'roomType' => '',
            'snapHotelImg' => '',
            'roomCount' => 0,
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['snapHotelName'] = $this->room['hotel']['name'];
        $snap['roomType'] = $this->room['room_type'];
        $snap['snapHotelImg'] = $this->room['hotel']['image']['url'];
        $snap['roomCount'] = $this->oRoom['room_count'];

        return $snap;
    }

    public function checkOrderStock($orderID)
    {
        $oRoom = HotelAppointmentModel::get($orderID);
        $this->oRoom = $oRoom;

        $this->room = $this->getRoomByOrder($oRoom);

        $status = $this->getOrderStatus();
        return $status;
    }

    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'orderPrice' => 0
        ];

            $rStatus = $this->getRoomStatus(
                $this->oRoom['hotel_room_id'],$this->oRoom['room_count'],$this->oRoom['check_in_time'],$this->oRoom['check_out_time'],$this->room
            );
            if (!$rStatus['haveStock']){
                $status['pass'] = false;
            }
            //计算金额
            $status['orderPrice'] = $rStatus['roomPrice'] - $this->getCouponPrice($this->oRoom['coupon_id']);
        return $status;
    }

    private function getCouponPrice($coupon_id)
    {
        $couponPrice = 0;
        if ($coupon_id) {
            $coupon = UserCoupon::where('status','=',1)
                ->find($coupon_id);
            if (!$coupon) {
                throw new OrderException([
                    'msg' => '优惠券已经被使用过了哦'
                ]);
            }
            $couponPrice = $coupon['money'];
        }
        return $couponPrice;
    }

    private function getRoomStatus($oRID, $oCount, $checkInTime, $checkOutTime, $room)
    {
        $rStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'checkInTime' => null,
            'checkOutTime' => null,
            'roomPrice' => 0
        ];

        if ($oRID != $room['id']){
            throw new OrderException([
                'msg' => 'id为'.$oRID.'房间不存在,创建订单失败'
            ]);
        }
        else{
            $rStatus['id'] = $room['id'];
            $rStatus['room_type'] = $room['room_type'];
            $rStatus['count'] = $oCount;
            $rStatus['checkInTime'] = $checkInTime;
            $rStatus['checkOutTime'] = $checkOutTime;
            if ($this->oRoom['type'] == 1) {
                $price = $room['price'];
            }else{
                $price = $room['group_price'];
            }
            $rStatus['roomPrice'] = $price*$oCount*diffBetweenTwoDays($checkInTime,$checkOutTime);
            if ($room['surplus_amount'] - $oCount >= 0){
                $rStatus['haveStock'] = true;
            }
        }
        return $rStatus;
    }

    public function getRoomByOrder($dataArray)
    {
        $room = HotelRoomType::getRoom($dataArray['hotel_room_id'],$dataArray['check_in_time'],$dataArray['check_out_time']);
        return $room;
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