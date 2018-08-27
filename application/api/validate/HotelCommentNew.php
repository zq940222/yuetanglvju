<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/2
 * Time: 19:32
 */

namespace app\api\validate;


class HotelCommentNew extends BaseValidate
{
    protected $rule = [
        'order_id' => 'require|isPositiveInteger',
        'overall_score' => 'require|isPositiveInteger',
        'location_score' => 'require|isPositiveInteger',
        'service_score' => 'require|isPositiveInteger',
        'sanitation_score' => 'require|isPositiveInteger',
        'facility_score' => 'require|isPositiveInteger',
        'content' => 'require'
    ];

    protected $message = [
        'order_id' => '订单ID不对哦',
        'overall_score' => '总体分数不正确',
        'location_score' => '环境分数不正确',
        'service_score' => '服务分数不正确',
        'sanitation_score' => '卫生分数不正确',
        'facility_score' => '设施分数不正确'
    ];
}