<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/2
 * Time: 14:22
 */

namespace app\hotel\controller;


use app\hotel\model\HotelAppointment;
use think\Db;

class Expend extends BaseController
{
    public function lists()
    {
        //今日
        $admin = session('admin');
        $now = strtotime(date('Y-m-d'));
        $today['today_amount'] = HotelAppointment::where("create_time>$now and status in(3,4,5,7)")
            ->where('hotel_id','=',$admin['hotel_id'])
            ->sum('total_price');//今日销售总额
        $today['today_order'] = HotelAppointment::where('create_time','>',$now)
            ->where('hotel_id','=',$admin['hotel_id'])
            ->where('status','in',[3,4,5,7])
            ->count();//今日订单数
        if ($today['today_order'] == 0) {
            $today['sign'] = round(0, 2);
        } else {
            $today['sign'] = round($today['today_amount'] / $today['today_order'], 2);
        }
        $this->assign('today',$today);
        return $this->fetch();
    }

    public function chart()
    {
        $date  = input('param.date/s');
        $admin = session('admin');

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $stime = strtotime($stime);
            $etime = strtotime($etime);
        }else{
            $stime =  strtotime("-1 month");//30天前
            $etime =  strtotime('+1 days');
        }
        $res = Db::name("HotelAppointment")
            ->field(" COUNT(*) as tnum,sum(total_price) as amount, FROM_UNIXTIME(create_time,'%Y-%m-%d') as gap ")
            ->where(" create_time > $stime and create_time < $etime  and status in(3,4,5) ")
            ->where('hotel_id','=',$admin['hotel_id'])
            ->group('gap')
            ->select();
        $tnum = 0;
        $tamount = 0;
        $arr = [];
        $brr = [];
        foreach ($res as $val){
            $arr[$val['gap']] = $val['tnum'];
            $brr[$val['gap']] = $val['amount'];
            $tnum += $val['tnum'];
            $tamount += $val['amount'];
        }

        for($i=$stime;$i<=$etime;$i=$i+24*3600){
            $tmp_num = array_key_exists(date('Y-m-d',$i),$arr) ? $arr[date('Y-m-d',$i)] :0;
            $tmp_amount = empty($brr[date('Y-m-d',$i)]) ? 0 : $brr[date('Y-m-d',$i)];
            $tmp_sign = empty($tmp_num) ? 0 : round($tmp_amount/$tmp_num,2);
            $order_arr[] = $tmp_num;
            $amount_arr[] = $tmp_amount;
            $sign_arr[] = $tmp_sign;
            $date = date('Y-m-d',$i);
            $list[] = array('day'=>$date,'order_num'=>$tmp_num,'amount'=>$tmp_amount,'sign'=>$tmp_sign,'end'=>date('Y-m-d',$i+24*60*60));
            $day[] = $date;
        }
        $result = array('order'=>$order_arr,'amount'=>$amount_arr,'sign'=>$sign_arr,'time'=>$day);
        return json($result);
    }
}