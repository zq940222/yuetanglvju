<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/2
 * Time: 9:57
 */

namespace app\hotel\controller;


use app\hotel\model\HotelAppointment;
use app\hotel\model\HotelComment;

class Comment extends BaseController
{
    public function lists()
    {
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $orderNo  = input('param.orderNo/s','');
        $date  = input('param.date/s');

        $admin = session('admin');
        $where['hotel_id'] = $admin['hotel_id'];
        if ($orderNo) {
            $appointment = HotelAppointment::where('order_no','=',$orderNo)->find();
            $where['id'] = $appointment['id'];
        }

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $where['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }

        $model = new HotelComment();
        $count  = $model->where($where)->count();
        $data  = $model->order('create_time desc')->where($where)->page($page, $limit)->select();
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function detail($id)
    {
        $model = new HotelComment();

        $data = $model->with(['hotel','user','image'])
            ->find($id);
        $this->assign('data',$data);
        return $this->fetch();
    }
}