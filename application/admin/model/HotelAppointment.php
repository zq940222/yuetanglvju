<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/17
 * Time: 17:58
 */

namespace app\admin\model;


use traits\model\SoftDelete;

class HotelAppointment extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getPayTimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }

    public function getStatusAttr($value)
    {
        $array = [
            -1 => '订单失败',
            1 => '待付款',
            2 => '待分享',
            3 => '待使用',
            4 => '待评价',
            5 => '已消费',
            6 => '已取消',
            7 => '申请退订',
            8 => '退订中',
            9 => '已退订'
        ];
        return $array[$value];
    }

    public function getPayChannelAttr($value)
    {
        $array = [
            '未支付',
            '余额',
            '微信',
            '支付宝'
        ];
        return $array[$value];
    }

    public function getTypeAttr($value)
    {
        $array = [
            '未知',
            '普通订单',
            '开团订单',
            '参团订单'
        ];
        return $array[$value];
    }

    public function getOccupantNameAttr($value)
    {
        return json_decode($value,true);
    }

    public function hotel()
    {
        return $this->belongsTo('Hotel','hotel_id','id');
    }

    public function user()
    {
        return $this->belongsTo('User','user_id','id');
    }

    public function groupList()
    {
        return $this->belongsTo('GroupList','group_list_id','id');
    }
}