<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/16
 * Time: 15:38
 */

namespace app\admin\controller;

use app\admin\model\Product;
use app\admin\model\User;
use app\api\model\HotelAppointment;
use app\admin\model\Order as OrderModel;
use app\admin\model\Hotel as HotelModel;
use think\Db;

class Statistics extends BaseController
{
    protected $beforeActionList=[
        'auth'=>['only'=>'hotel,product']
    ];
    public function index()
    {
        //入住酒店数量
        $hotelModel = new HotelModel();
        $hotelCount = $hotelModel->where('status','=',1)->count();
        //注册用户数
        $userModel = new User();
        $userCount = $userModel->count();
        //商品数量
        $productModel = new Product();
        $productCount = $productModel->where('status','=',1)->count();
        //酒店订单数量
        $hotelOrderCount = HotelAppointment::where('status','in',[3,4,5])->count();
        //产品订单数量
        $productOrderCount = OrderModel::where('status','in',[2,3,4])->count();
        $data = [
            'hotelCount' => $hotelCount,
            'userCount' => $userCount,
            'productCount' => $productCount,
            'hotelOrderCount' => $hotelOrderCount,
            'productOrderCount' => $productOrderCount
        ];
        $this->assign('data',$data);
        //系统信息
        $sys_info['os']             = PHP_OS;
        $sys_info['zlib']           = function_exists('gzclose') ? 'YES' : 'NO';//zlib
        $sys_info['safe_mode']      = (boolean) ini_get('safe_mode') ? 'YES' : 'NO';//safe_mode = Off
        $sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
        $sys_info['curl']			= function_exists('curl_init') ? 'YES' : 'NO';
        $sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
        $sys_info['phpv']           = phpversion();
        $sys_info['ip'] 			= GetHostByName($_SERVER['SERVER_NAME']);
        $sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
        $sys_info['max_ex_time'] 	= @ini_get("max_execution_time").'s'; //脚本最大执行时间
        $sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false;
        $sys_info['domain'] 		= $_SERVER['HTTP_HOST'];
        $sys_info['memory_limit']   = ini_get('memory_limit');
        $mysqlinfo = Db::query("SELECT VERSION() as version");
        $sys_info['mysql_version']  = $mysqlinfo[0]['version'];
        if(function_exists("gd_info")){
            $gd = gd_info();
            $sys_info['gdinfo'] 	= $gd['GD Version'];
        }else {
            $sys_info['gdinfo'] 	= "未知";
        }
        $this->assign('sys_info',$sys_info);
        return $this->fetch();
    }

    public function hotel()
    {
        //今日
        $now = strtotime(date('Y-m-d'));
        $today['today_amount'] = HotelAppointment::where("create_time>$now and status in(3,4,5,7)")->sum('total_price');//今日销售总额
        $today['today_order'] = HotelAppointment::where('create_time','>',$now)->where('status','in',[3,4,5,7])->count();//今日订单数
        if ($today['today_order'] == 0) {
            $today['sign'] = round(0, 2);
        } else {
            $today['sign'] = round($today['today_amount'] / $today['today_order'], 2);
        }
        $this->assign('today',$today);
        return $this->fetch();
    }

    public function hotelChart()
    {
        $date  = input('param.date/s');

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

    public function product()
    {
        //今日
        $now = strtotime(date('Y-m-d'));
        $today['today_amount'] = OrderModel::where("create_time>$now and status in(2,3,4,6)")->sum('pay_price');//今日销售总额
        $today['today_order'] = OrderModel::where('create_time','>',$now)->where('status','in',[2,3,4,6])->count();//今日订单数
        if ($today['today_order'] == 0) {
            $today['sign'] = round(0, 2);
        } else {
            $today['sign'] = round($today['today_amount'] / $today['today_order'], 2);
        }
        $this->assign('today',$today);
        return $this->fetch();
    }

    public function productChart()
    {
        $date  = input('param.date/s');

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $stime = strtotime($stime);
            $etime = strtotime($etime);
        }else{
            $stime =  strtotime("-1 month");//30天前
            $etime =  strtotime('+1 days');
        }
        $res = Db::name('Order')->field(" COUNT(*) as tnum,sum(order_total_price) as amount, FROM_UNIXTIME(create_time,'%Y-%m-%d') as gap ")
            ->where(" create_time > $stime and create_time < $etime  and status in(2,3,4) ")
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