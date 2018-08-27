<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/19
 * Time: 17:57
 */

namespace app\api\validate;


class GetRoom extends BaseValidate
{
    protected $rule = [
        'hotel_id' => 'require|isPositiveInteger',
        'check_in_time' => 'require|date',
        'check_out_time' => 'require|date'
    ];

    protected $message = [
        'hotel_id' => 'hotel_id必须是正整数',
        'check_in_time' => '入住时间不对哦',
        'check_out_time' => '离店时间不对哦'
    ];
}