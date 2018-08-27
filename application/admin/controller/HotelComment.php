<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/16
 * Time: 14:22
 */

namespace app\admin\controller;

use app\admin\model\Hotel as HotelModel;
use app\api\model\HotelAppointment;
use app\admin\model\HotelComment as HotelCommentModel;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class HotelComment extends BaseController
{
    public function lists()
    {
        $hotel = HotelModel::all();
        $this->assign('hotel',$hotel);
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $orderNo  = input('param.orderNo/s','');
        $hotelID  = input('param.hotel_id/d',0);
        $date  = input('param.date/s');

        $where=[];
        if ($orderNo) {
            $appointment = HotelAppointment::where('order_no','=',$orderNo)->find();
            $where['id'] = $appointment['id'];
        }

        if ($hotelID) {
            $where['hotel_id'] = $hotelID;
        }

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $where['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }

        $model = new HotelCommentModel();
        $count  = $model->where($where)->count();
        $data  = $model->order('create_time desc')->where($where)->relation(['hotel'])->page($page, $limit)->select();
        foreach ($data as &$value) {
            $value['hotel_name'] = $value->hotel->name;
        }
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function detail($id)
    {
        $model = new HotelCommentModel();

        $data = $model->with(['hotel','user','image'])
            ->find($id);
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function delete()
    {
        $id=input('param.id/a');
        $res = HotelCommentModel::destroy($id);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }
}