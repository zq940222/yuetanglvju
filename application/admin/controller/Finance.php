<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/23
 * Time: 17:38
 */

namespace app\admin\controller;

use app\admin\model\Hotel as HotelModel;
use app\admin\model\Finance as FinanceModel;

class Finance extends BaseController
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
        $hotelID = input('param.hotel_id/d');
        $type  = input('param.type/d');
        $date  = input('param.date/s');


        $where=[];


        if($type) {
            $where['type'] = $type;
        }

        if($hotelID) {
            $where['hotel_id']=$hotelID;
        }

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $where['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }

        $data  = FinanceModel::order('create_time DESC')
            ->where($where)
            ->page($page,$limit)
            ->select()
            ->toArray();
        $count = FinanceModel::where($where)
            ->count();
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }
}