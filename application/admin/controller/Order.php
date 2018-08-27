<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/16
 * Time: 9:13
 */

namespace app\admin\controller;

use app\admin\model\Order as OrderModel;

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

        if($status) {
            $where['status'] = $status;
        }

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $where['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }
        $orderModel = new OrderModel();
        $count  = $orderModel->where($where)->count();

        $data  = $orderModel->where($where)->page($page, $limit)->select();

        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function detail()
    {
        $id = input('param.id/d', 0);
        $order = OrderModel::get($id,['orderProduct','province','city','district']);

        $this->assign('order', $order);
        return $this->fetch();
    }

    public function saveStatus()
    {
        if(request()->isPost()) {
            $order_id = input('post.id/d');
            $status = input('post.status');
            $data = OrderModel::get($order_id);
            $data->status = $status;
            if($data->save()) {
                $this->redirect('lists');
            } else {
                $this->error('修改失败');
            }
        }
    }
}