<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/16
 * Time: 9:32
 */

namespace app\admin\model;


class Order extends BaseModel
{
    public function getStatusAttr($value)
    {
        $array = [
            -1 => '订单失败',
            1  => '待付款',
            2  => '待收货',
            3  => '待评价',
            4  => '已完成',
            5  => '已取消',
            6  => '退货申请',
            7  => '退货中',
            8  => '已退货'
        ];
        return $array[$value];
    }

    public function getPayChannelAttr($value) {
        $array = [
            '未支付',
            '余额',
            '微信',
            '支付宝'
        ];
        return $array[$value];
    }

    public function orderProduct()
    {
        return $this->hasMany('OrderProduct','order_id','id');
    }

    public function province()
    {
        return $this->belongsTo('Region','province','id');
    }

    public function city()
    {
        return $this->belongsTo('Region','city','id');
    }

    public function district()
    {
        return $this->belongsTo('Region','district','id');
    }
}