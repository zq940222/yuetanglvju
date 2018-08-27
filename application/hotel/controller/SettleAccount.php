<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/2
 * Time: 14:18
 */

namespace app\hotel\controller;


use app\hotel\model\HotelSettleAccounts;

class SettleAccount extends BaseController
{
    public function lists()
    {
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $date  = input('param.date/s');

        $admin = session('admin');
        $where['hotel_id'] = $admin['hotel_id'];

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $where['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }

        $data  = HotelSettleAccounts::order('create_time DESC')
            ->where($where)
            ->page($page,$limit)
            ->select();
        $count = HotelSettleAccounts::where($where)
            ->count();
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }
}