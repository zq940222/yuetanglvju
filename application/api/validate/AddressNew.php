<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/8
 * Time: 16:32
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{
    protected $rule = [
        'consignee' => 'require|isNotEmpty',
        'mobile' => 'require|isMobile',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'district' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty'
    ];

    protected $message = [
        'consignee' => '收件人不能为空',
        'mobile' => '手机号不正确哦',
        'province' => '省不能为空',
        'city' => '市不能为空',
        'district' => '区不能为空',
        'detail' => '详细地址不能为空'
    ];
}