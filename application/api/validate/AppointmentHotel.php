<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/15
 * Time: 10:23
 */

namespace app\api\validate;


class AppointmentHotel extends BaseValidate
{
    protected $rule = [
        'type' => 'require|isPositiveInteger',
        'check_in_time' => 'require|date',
        'check_out_time' => 'require|date',
        'hotel_room_id' => 'require|isPositiveInteger',
        'room_count' => 'require|isPositiveInteger',
        'latest_time' => 'require|date',
        'occupant_name' => 'require',
        'mobile' => 'require|isMobile',
        'coupon_id' => 'require',
        'invoice' => 'require',
        'group_list_id' => 'require|\d',
    ];

    protected $message = [
        'type' => '订单类型不能为空',
        'check_in_time' => '入住时间不符合要求',
        'check_out_time' => '退房时间不符合要求',
        'hotel_room_id' => '房间ID不对哦',
        'room_count' => '房间数不对哦',
        'latest_time' => '到店时间不符合要求哦',
        'occupant_name' => '入住人不能为空哦',
        'mobile' => '手机号不正确哦',
        'coupon_id' => '优惠券不符合要求哦',
        'is_invoice' => '开发票不能为空哦',
        'group_list_id' => '拼团列表ID不对哦'
    ];
}