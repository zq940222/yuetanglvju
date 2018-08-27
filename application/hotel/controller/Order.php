<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/1
 * Time: 19:46
 */

namespace app\hotel\controller;


use app\admin\model\Setting;
use app\hotel\model\Hotel;
use app\hotel\model\HotelAppointment;
use app\hotel\model\HotelSettleAccounts;
use app\hotel\model\User;
use app\lib\exception\HotelAdminException;
use app\lib\exception\SuccessMessage;
use think\Db;
use think\Exception;
use think\Log;

class Order extends BaseController
{
    public function lists()
    {
        return $this->fetch();
    }

    public function page()
    {
        $page       = input('param.page/d', 1);
        $limit      = input('param.limit/d');
        $orderNo    = input('param.order_no', '');
        $userID     = input('param.user_id', 0);
        $payChannel = input('param.pay_channel', 0);
        $status     = input('param.status', 0);
        $date       = input('param.date', '');

        $admin = session('admin');
        $where['hotel_id'] = $admin['hotel_id'];
        if($orderNo) {
            $where['order_no'] = $orderNo;
        }
        if($userID) {
            $where['user_id'] = $userID;
        }
        if($payChannel) {
            $where['pay_channel'] = $payChannel;
        }

        if($status) {
            $where['status'] = $status;
        }

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $where['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }
        $orderModel = new HotelAppointment();
        $count  = $orderModel->where($where)->count();

        $data  = $orderModel->order('create_time desc')->where($where)->page($page, $limit)->select();

        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function detail($id)
    {
        $order = HotelAppointment::get($id,['user','hotel','groupList','groupList.groupHost']);
        $this->assign('order', $order);
        return $this->fetch();
    }

    public function edit($id)
    {
        $admin = session('admin');
        $postData = request()->post();
        $orderModel = HotelAppointment::get($id);
        if ($orderModel->hotel_id != $admin['hotel_id']) {
            throw new HotelAdminException(['msg' => '你没有权限这么做']);
        }
        if ($orderModel->status != '待使用') {
            throw new HotelAdminException(['msg' => '该订单不是待使用状态']);
        }
        Db::startTrans();
        try{
            $orderModel->hotel_remark = $postData['hotel_remark'];
            $orderModel->status = $postData['status'];
            $orderModel->save();
            $settingModel = model('setting')->find();
            if ($orderModel->status == '待评价' && ($orderModel->pay_channel == '微信' || $orderModel->pay_channel == '支付宝')) {
                //TODO:: 积分结算
                $userModel = User::get($orderModel->user_id);
                $userModel->setInc('integral',$orderModel->total_price*$settingModel['integral_ratio']/100);
            }
            //TODO::商家抽成结算
            if ($orderModel->status == '待评价')
            {
                $settleAccountsModel = new HotelSettleAccounts();
                $settleAccountsModel->hotel_id = $orderModel->hotel_id;
                $settleAccountsModel->hotel_name = $orderModel->snap_hotel_name;
                $totalPrice = $orderModel->room_real_price*$orderModel->room_count*(diffBetweenTwoDays($orderModel->check_in_time,$orderModel->check_out_time));
                $settleAccountsModel->room_total_price = $totalPrice;
                $settleAccountsModel->coupon_price = $orderModel->coupon_price;
                $settleAccountsModel->commission_price = $totalPrice*$settingModel['royalty_ratio']/100;
                $settleAccountsModel->settle_accounts_price = $totalPrice - $settleAccountsModel->commission_price;
                $settleAccountsModel->save();

                $hotelModel = Hotel::get($orderModel->hotel_id);
                $hotelModel->setInc('income',$settleAccountsModel->settle_accounts_price);
                $hotelModel->setInc('can_withdraw',$settleAccountsModel->settle_accounts_price);
            }
            Db::commit();
            return json(new SuccessMessage(['msg' => '提交成功']));
        }
        catch (Exception $ex){
            Db::rollback();
            Log::error($ex);
            throw new HotelAdminException(['msg' => '操作失败']);
        }

    }

}