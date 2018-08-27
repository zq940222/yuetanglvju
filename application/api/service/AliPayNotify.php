<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/25
 * Time: 18:00
 */

namespace app\api\service;

use app\admin\model\Setting;
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

Loader::import('AliPay.aop.AopClient',EXTEND_PATH,'.php');

class AliPayNotify
{
    protected $orderModel;
    public function notify($type)
    {
        $aop = new \AopClient;
        $aop->alipayrsaPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAudjeCjTYyDO6z2hHnUr+/R7Wz6PwpH20FTADbdgpUJu+3sC42jo/DZsSYem8UCgO8Q2lM0HzzcXgUOgJVwft8arr9pqCuc7JeqAR2G4JweQKJI81jFtTy8ZFT1n5YmjdSy3OOUyb235560FLT3+0HAuRlKV3+yGeA3zHC7ymRR2KGvCz2hkk+Tt2woP8w+j4pU0t+WQoaUllgtXg/SPlGJNKPMjxAqihK7le2Hlt6t5P+BdqKgxb50SGpiGETrnbZpGkOlHtJjrmqBgTRQbmLUmK7qDGSXiZmwfwLftPykmNwpb7wJL6a98NHDixB+WO6TPc8zanZEjgyWQiw5HEbQIDAQAB";
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
        if (!$flag) {
            return 'failure';
        }

        if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
            if ($type == "hotel") {
                $this->hotelNotify($_POST['out_trade_no']);
            } elseif ($type == "product") {
                $this->productNotify($_POST['out_trade_no']);
            } elseif ($type == "recharge") {
                $this->rechargeNotify($_POST['out_trade_no']);
            }
            return 'success';
        } else {
            //不再发消息
            return 'success';
        }

    }

    private function rechargeNotify($orderNo)
    {
        Db::startTrans();
        try{
            //修改充值信息
            $rechargeModel = Recharge::where('order_no','=',$orderNo)
                ->where('status','=',1)
                ->lock(true)
                ->find();
            if ($rechargeModel)
            {
                $this->orderModel = $rechargeModel;
                $rechargeModel->status = 2;
                $rechargeModel->pay_channel = 2;
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
            return 'success';
        }
        catch (Exception $ex){
            Db::rollback();
            Log::error($ex);
            return 'success';
        }
    }

    private function productNotify($orderNo)
    {
        Db::startTrans();
        try{
            $orderModel = Order::where('order_no','=',$orderNo)
                ->where('status','=',1)
                ->lock(true)
                ->find();
            if ($orderModel)
            {
                $this->orderModel = $orderModel;
                //修改订单状态
                $orderModel->status = OrderStatusEnum::UnDelivery;
                $orderModel->pay_channel = 3;
                $orderModel->pay_time = time();
                $orderModel->save();
//                //积分结算
//                $userModel = User::get($orderModel->user_id);
//                $userModel->setDec('integral',$orderModel->integral);
                //减库存
                $this->reduceStock($orderModel->id);
            }

            Db::commit();
            return 'success';
        }
        catch (Exception $ex){
            Db::rollback();
            Log::error($ex);
            return 'success';
        }
    }

    private function reduceStock($orderID)
    {
        $orderProduct = OrderProduct::where('order_id','=',$orderID)->select();
        foreach ($orderProduct as $singleOProduct)
        {
            if ($singleOProduct['spec_key']) {
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

    private function hotelNotify($orderNo)
    {
        Db::startTrans();
        try{
            $orderModel = HotelAppointmentModel::where('order_no','=',$orderNo)
                ->where('status','=',1)
                ->lock(true)
                ->find();
            if ($orderModel)
            {
                $this->orderModel = $orderModel;
                if ($orderModel->type == 1){
                    $status = HotelAppointmentStatusEnum::UnUse;
                }else {
                    $status = HotelAppointmentStatusEnum::UnShare;
                }
                $orderModel->status = $status;
                $orderModel->pay_channel = 3;
                $orderModel->pay_time = time();
                $orderModel->save();
                //优惠券结算
                if ($orderModel->coupon_id){
                    UserCoupon::destroy($orderModel->coupon_id);
                }
                //积分结算
//            $setModel = Setting::get(1);
//            $userModel = User::get($orderModel->user_id);
//            $userModel->setInc('integral',$orderModel->total_price*$setModel['integral_ratio']/100);
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
            return 'success';
        }
        catch (Exception $ex){
            Db::rollback();
            Log::error($ex);
            return 'success';
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