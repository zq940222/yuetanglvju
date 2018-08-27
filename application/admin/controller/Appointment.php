<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/17
 * Time: 16:56
 */

namespace app\admin\controller;

use app\admin\model\Hotel as HotelModel;
use app\admin\model\HotelAppointment;

class Appointment extends BaseController
{
    public function lists()
    {
        $hotel = HotelModel::all();
        $this->assign('hotel',$hotel);
        return $this->fetch();
    }

    public function page()
    {
        $page       = input('param.page/d', 1);
        $limit      = input('param.limit/d');
        $orderNo    = input('param.order_no', '');
        $userID     = input('param.user_id', 0);
        $payChannel = input('param.pay_channel', 0);
        $hotelID    = input('param.hotel_id',0);
        $status     = input('param.status', 0);
        $date       = input('param.date', '');

        $where = [];
        if($orderNo) {
            $where['order_no'] = $orderNo;
        }
        if($userID) {
            $where['user_id'] = $userID;
        }
        if($payChannel) {
            $where['pay_channel'] = $payChannel;
        }

        if ($hotelID) {
            $where['hotel_id'] = $hotelID;
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

        $data  = $orderModel->with(['hotel'])->order('create_time desc')->where($where)->page($page, $limit)->select();
        foreach ($data as &$value) {
            $value['hotel_name'] = $value->hotel->name;
        }

        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function detail()
    {
        $id = input('param.id/d', 0);
        $order = HotelAppointment::get($id,['user','hotel','groupList','groupList.groupHost']);
        $this->assign('order', $order);
        return $this->fetch();
    }

}