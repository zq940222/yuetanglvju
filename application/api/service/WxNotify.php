<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/30
 * Time: 14:55
 */

namespace app\api\service;

use app\api\model\BookingStatistics;
use app\api\model\GroupList;
use app\api\model\HotelRoomType;
use app\api\model\Order;
use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\Recharge;
use app\api\model\SpecProductPrice;
use app\api\model\User;
use app\api\model\UserAccountDetails;
use app\api\model\UserCoupon;
use app\lib\enum\HotelAppointmentStatusEnum;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\api\model\HotelAppointment as HotelAppointmentModel;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class WxNotify extends \WxPayNotify
{
    public function NotifyProcess($data, &$msg)
    {
        if ($data['result_code'] == 'SUCCESS')
        {
            $orderNo = $data['out_trade_no'];
            $type = $data['attach'];
            if ($type == 'hotel'){
                return $this->hotelNotify($orderNo);
            }elseif ($type == 'product'){
                return $this->productNotify($orderNo);
            }elseif ($type == 'recharge'){
                return $this->rechargeNotify($orderNo);
            }
            return true;
        }
        else{
            // 不再发送消息
            return true;
        }
    }

    private function hotelNotify($orderNo)
    {
        Db::startTrans();
        try{
            $orderModel = HotelAppointmentModel::where('order_no','=',$orderNo)
                ->lock(true)
                ->find();
            if ($orderModel->status == HotelAppointmentStatusEnum::UnPay)
            {
                $this->orderModel = $orderModel;
                if ($orderModel->type == 1){
                    $status = HotelAppointmentStatusEnum::UnUse;
                }else {
                    $status = HotelAppointmentStatusEnum::UnShare;
                }
                $orderModel->status = $status;
                $orderModel->pay_channel = 2;
                $orderModel->pay_time = time();
                $orderModel->save();
                //优惠券结算
                if ($orderModel->coupon_id){
                    UserCoupon::destroy($orderModel->coupon_id);
                }
                //添加预定房间
                $this->addBooking();
                //检查参团后是否完成拼团
                if ($orderModel->type == 3) {
                    $this->checkGroupList();
                }
                //开团创建开团列表
                if ($orderModel->type == 2) {
                    $this->createGroupList();
                }
            }
            Db::commit();
            return true;
        }
        catch (Exception $ex){
            Db::rollback();
            Log::error($ex);
            return true;
        }

    }

    private function rechargeNotify($orderNo)
    {
        Db::startTrans();
        try{
            //修改充值信息
            $rechargeModel = Recharge::where('order_no','=',$orderNo)
                ->lock(true)
                ->find();
            if ($rechargeModel->status == 1)
            {
                $this->orderModel = $rechargeModel;
                $rechargeModel->status = 2;
                $rechargeModel->pay_channel = 1;
                $rechargeModel->pay_time = time();
                $rechargeModel->save();
                //用户余额修改
                $userModel = User::get($rechargeModel->user_id);
                $userModel->setInc('balance',$rechargeModel->total_price);
                //添加记录
                $accountDetailModel = new UserAccountDetails();
                $accountDetailModel->user_id = $rechargeModel->user_id;
                $accountDetailModel->type = 2;
                $accountDetailModel->money = $rechargeModel->total_price;
                $accountDetailModel->status = 2;
                $accountDetailModel->save();
            }
            Db::commit();
            return true;
        }
        catch (Exception $ex){
            Db::rollback();
            Log::error($ex);
            return true;
        }
    }

    private function productNotify($orderNo)
    {
        Db::startTrans();
        try{
            $orderModel = Order::where('order_no','=',$orderNo)
                ->lock(true)
                ->find();
            if ($orderModel->status == OrderStatusEnum::UnPay)
            {
                $this->orderModel = $orderModel;
                //修改订单状态
                $orderModel->status = OrderStatusEnum::UnDelivery;
                $orderModel->pay_channel = 2;
                $orderModel->pay_time = time();
                $orderModel->save();
                //减库存
                $this->reduceStock($orderModel->id);
            }
            Db::commit();
            return true;
        }
        catch (Exception $ex){
            Db::rollback();
            Log::error($ex);
            return true;
        }
    }

    private function reduceStock($order_id)
    {
        $orderProduct = OrderProduct::where('order_id','=',$order_id)->select();
        foreach ($orderProduct as $singleOProduct)
        {
            if ($singleOProduct->spec_key) {
                SpecProductPrice::where('product_id','=',$singleOProduct['product_id'])
                    ->where('key','=',$singleOProduct['spec_key'])
                    ->setDec('store',$singleOProduct['product_num']);
            }else {
                Product::where('id','=',$singleOProduct['product_id'])
                    ->setDec('store',$singleOProduct['product_num']);
            }
            Product::where('id','=',$singleOProduct['product_id'])
                ->setInc('num_sold',$singleOProduct['product_num']);
        }
    }
    private function createGroupList()
    {
        $hotelModel = \app\api\model\Hotel::get($this->orderModel->hotel_id);
        $groupListModel = new GroupList();
        $groupListModel->hotel_id = $this->orderModel->hotel_id;
        $groupListModel->group_host_id = $this->orderModel->user_id;
        $groupListModel->endtime = time()+24*60*60;
        $groupListModel->group_num = $hotelModel->group_num;
        $groupListModel->save();

    }

    private function checkGroupList()
    {
        $groupListID = $this->orderModel->group_list_id;
        $groupListModel = GroupList::get($groupListID,['appointment']);
        $groupListModel->setInc('join_group_num');
        if ($groupListModel->group_num == $groupListModel->join_group_num) {
            $groupListModel->status = 2;
        }
        $groupListModel->save();

        //完成拼团更改订单状态
        if ($groupListModel->status == 2) {
            $model = new HotelAppointmentModel();
            $model->save(['status'=> 3],['group_list_id'=> $groupListModel->id,'status'=>2]);
        }
    }

    private function addBooking()
    {
        $roomID = $this->orderModel->hotel_room_id;
        $bookingNum = $this->orderModel->room_count;
        $checkInTime = $this->orderModel->check_in_time;
        $checkOutTime = $this->orderModel->check_out_time;
        $dayNum = diffBetweenTwoDays($checkInTime,$checkOutTime);
        for ($i=0;$i<$dayNum;$i++) {
            $date = date("Y-m-d",strtotime("+$i day",strtotime($checkInTime)));
            $bookingModel = BookingStatistics::where('date',$date)
                ->where('room_id',$roomID)->find();
            if (!$bookingModel) {
                $roomModel = HotelRoomType::get($roomID);
                BookingStatistics::create([
                    'room_id' => $roomID,
                    'date' => $date,
                    'booking_num' => 0,
                    'surplus_amount' => $roomModel->stock
                ]);
                $bookingModel = BookingStatistics::where('date',$date)
                    ->where('room_id',$roomID)->find();
            }
            $bookingModel->setInc('booking_num',$bookingNum);
            $bookingModel->setDec('surplus_amount',$bookingNum);
        }
        return true;
    }
}